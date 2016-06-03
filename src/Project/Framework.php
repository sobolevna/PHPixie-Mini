<?php

namespace Project;

class Framework extends \PHPixie\Framework {

    protected $routeCount = array();

    /**
     * 
     * @return \Project\Framework\Builder
     */
    protected function buildBuilder() {
        return new Framework\Builder();
    }

    /**
     * 
     * @param string $pattern -- pattern in standard PHPixie format
     * @param callable $func -- a callback that must have a single param -- Request $request
     * It works like method of PHPixie\HTTPProcessor\Action
     */
    public function route($pattern, $func) {
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
        $pattern = $pattern ? $pattern : '';
        $pattern = $pattern && $pattern[0] == '/' ? substr($pattern, 1) : $pattern;
        $cnt = count($this->routeCount);
        $this->routeCount[] = 'r' . $cnt;
        $id = $this->routeCount[$cnt];
        $config = $this->builder->configuration()
                ->httpConfig()->slice('resolver.resolvers');
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
     * @param string $name Connection name
     * @param array $config Configuration data in PHPixie format. Is to contain 
     * such fields as <b>driver</b>, <b>connection</b>, <b>user</b> 
     * and <b>password</b> (the last two are unneeded for SQLite)
     */
    public function confugureDB($name, array $config) {
        $this->sanitizeDB($config);
        $this->builder->configuration()->databaseConfig()->set($name, $config);
    }

    /**
     * 
     * @param string $name ORM Model name
     * @param array $config Configuration data in PHPixie format.
     */
    public function ormModel($name, array $config) {
        $this->builder->configuration()->ormConfig()
            ->slice('models')->set($name, $config);
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
        if (in_array(
                $type, array('repository', 'entity', 'embeddedEntity', 'query')
            ) && is_callable($func)
        ) {
            $wrappers = $this->builder()->configuration()->ormWrappers();
            $wrappers->{'make' . ucfirst($type)}($name, $func);
            return $this;
        } else {
            throw new \PHPixie\ORM\Exception\Builder('Invalid wrapper type');
        }
    }

    public function setAuthProviders($name, $config) {
        $this->builder->configuration()->authConfig()
            ->slice('domains.default.provider')->set($name, $config);
    }

    protected function sanitizeORM(array $config) {
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
        throw new \PHPixie\ORM\Exception\Relationship(
            'Invalid ORM relationships configuration'
        );
    }

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

}
