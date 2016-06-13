<?php

namespace PHPixie\Micro;

class Framework extends \PHPixie\Framework {
    
    /**
     * @var \PHPixie\Micro\Framework\Builder
     */
    protected $builder;

    /**
     * 
     * @return \PHPixie\Micro\Framework\Builder
     */
    protected function buildBuilder() {
        return new Framework\Builder();
    }

    /**
     * 
     * @param string $ptrn -- pattern in standard PHPixie format
     * @param callable $func -- a callback that must have a single param -- Request $request
     * It works like method of PHPixie\HTTPProcessor\Action
     */
    public function route($ptrn, $func) {
        $pattern = $this->sanitizeRoute($ptrn, $func);
        $config = $this->builder->configuration()
                ->httpConfig()->slice('resolver.resolvers');
        $cnt = count($config->getData());
        $id  = 'r' . $cnt;
        $config->set(
            $id, array(
                'type' => 'pattern',
                'path' => $pattern,
                'defaults' => array(
                    'processor' => 'act',
                    'action' => $id
                )
            )
        );
        $proc = $this->builder->configuration()
                ->httpProcessor()->processor('act');
        $proc->{$id . 'Action'} = \Closure::bind($func, $proc);
        return $this;
    }

    /**
     * 
     * @param string $pattern
     * @param Closure $func
     * @return string
     * @throws \PHPixie\HTTPProcessors\Exception
     * @throws \PHPixie\Route\Exception\Route
     */
    protected function sanitizeRoute($pattern, $func) {
        if (!is_callable($func)) {
            throw new \PHPixie\HTTPProcessors\Exception(
            'Invalid callback for action'
            );
        }
        if (!(is_string($pattern) || is_numeric($pattern))) {
            throw new \PHPixie\Route\Exception\Route(
            'Invalid route pattern'
            );
        }
        $ret = $pattern ? ($pattern[0] == '/' ? substr($pattern, 1) : $pattern) : '';
        return $ret;
    }

    /**
     * 
     * @param string $name Connection name
     * @param array $config Configuration data in PHPixie format. Is to contain 
     * such fields as <b>driver</b>, <b>connection</b>, <b>user</b> 
     * and <b>password</b> (the last two are unneeded for SQLite)
     */
    public function confugureDB($name, array $config) {
        $this->sanitizeDB($config);
        $this->builder->configuration()->databaseConfig()->set($name, $config);
        return $this;
    }

    /**
     * 
     * @param array $config
     * @return boolean
     * @throws \PHPixie\Database\Exception\Builder
     */
    protected function sanitizeDB(array $config) {
        if (array_key_exists('driver', $config)) {
            if (array_key_exists('connection', $config)) {
                return true;
            } elseif ($config['driver'] == 'mongo' && array_key_exists('database', $config)) {
                return true;
            }
        }
        throw new \PHPixie\Database\Exception\Builder(
        'Invalid database configuration'
        );
    }

    /**
     * 
     * @param string $name ORM Model name
     * @param array $config Configuration data in PHPixie format.
     */
    public function ormModel($name, array $config) {
        $this->sanitizeModel($name, $config);
        $this->builder->configuration()->ormConfig()
            ->slice('models')->set($name, $config);
        return $this;
    }

    /**
     * 
     * @param string $name
     * @param array $config
     * @throws \PHPixie\ORM\Exception\Model
     */
    protected function sanitizeModel($name, $config) {
        if (!is_scalar($name)) {
            throw new \PHPixie\ORM\Exception\Model(
            'Invalid model name'
            );
        }
        $keys = ['type', 'connection', 'idField'];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $config)) {
                throw new \PHPixie\ORM\Exception\Model(
                'Invalid model config'
                );
            }
        }
    }

    /**
     * 
     * @param array $config Configuration data in PHPixie format.
     */
    public function ormRelationship(array $config) {
        $this->sanitizeORM($config);
        $conf = $this->builder->configuration()->ormConfig()
            ->get('relationships');
        $conf[] = $config;
        $this->builder->configuration()->ormConfig()
            ->set('relationships', $conf);
        return $this;
    }

    /**
     * 
     * @param array $config
     * @return boolean
     * @throws \PHPixie\ORM\Exception\Relationship
     */
    protected function sanitizeORM(array $config) {
        if (array_key_exists('type', $config)) {
            if (($config['type'] == 'oneToMany' || $config['type'] == 'oneToOne' || $config['type'] == 'embedsMany') && array_key_exists('owner', $config) && array_key_exists('items', $config)
            ) {
                return true;
            } elseif ($config['type'] == 'manyToMany' && \array_key_exists('left', $config) && \array_key_exists('right', $config)
            ) {
                return true;
            } elseif ($config['type'] == 'embedsOne' && array_key_exists('owner', $config) && \array_key_exists('item', $config)
            ) {
                return true;
            } elseif ($config['type'] == 'nestedSet' && \array_key_exists('model', $config)
            ) {
                return true;
            }
        }
        throw new \PHPixie\ORM\Exception\Relationship(
        'Invalid ORM relationships configuration'
        );
    }

    /**
     * 
     * @param string $type Type of a wrapper -- the only options are:
     *  'repository', 'entity', 'embeddedEntity' and 'query'
     * @param string $name Wrapper name
     * @param function $func Function that must have a single 
     * parameter and return an instance of \PHPixie\ORM\Wrappers\Type
     */
    public function wrapORM($type, $name, $func) {
        $types = array('repository', 'entity', 'embeddedEntity', 'query');
        if (in_array(
                $type, $types
            ) && is_callable($func)
        ) {
            $wrappers = $this->builder()->configuration()->ormWrappers();
            $wrappers->{'make' . ucfirst($type)}($name, $func);
        } else {
            throw new \PHPixie\ORM\Exception\Builder('Invalid wrapper type');
        }
        return $this;
    }

    /**
     * 
     * @param array $config
     * @throws \PHPixie\Auth\Exception
     */
    public function setAuthProviders(array $config) {
        if (!$config || $config == []) {
            throw new \PHPixie\Auth\Exception('Invalid auth providers');
        }
        $this->builder->configuration()->authConfig()
            ->slice('domains.default')->set('providers', $config);
        return $this;
    }

    /**
     * 
     * @param mixed $flag Flag that can handle creating template directory and .htacess file
     */
    public function run($flag = 0) {
        if ($flag) {
            $this->handleRunFlag();
        }
        $this->registerDebugHandlers();
        $this->processHttpSapiRequest();
    }

    protected function handleRunFlag() {
        $root = realpath(dirname(filter_input(INPUT_SERVER, 'SCRIPT_FILENAME')));
        $access = file_exists($root . '/.htaccess');
        $tpl = file_exists($root . '/template');
        if (!$access) {
            echo $this->errorReport('.htaccess file');
            $file = fopen($root . '/.htaccess', 'w');
            $base = filter_input(INPUT_SERVER, 'PHP_SELF');
            $text = <<<TEXT
RewriteEngine On
RewriteBase $base
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule .* index.php [PT,L,QSA]
TEXT;
            fwrite($file, $text);
            fclose($file);
        }
        if (!$tpl) {
            echo $this->errorReport('template directory');

            mkdir($root . '/template');
        }
    }

    protected function errorReport($param) {
        return "
<h2>Oops!</h2>
<p>It seems like you don't have $param in your document root.</p>
<p>Just refresh the page and we shall make it for you.</p>
<p>If you see an error then your server settings prevent PHPixie to change your filesystem.</p>
<p>Change your server settings or just write \"\$app->run(0);\" in your index file to shut this function down</p>
            ";
    }

}
