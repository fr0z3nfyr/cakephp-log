<?php

App::uses('ModelBehavior', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('Log', 'Log.Model');

/**
 * Class LogBehavior
 */
class LogBehavior extends ModelBehavior {

    /**
     * @var array
     */
    protected $_defaultConfig = array(
        'userFields' => array(
            'id', 'name', 'username', 'email'
        )
    );

    /**
     * @param Model $Model
     * @param array $config
     */
    public function setup(Model $Model, $config = array())
    {
        $this->settings[$Model->alias] = $this->_defaultConfig;
        $this->settings[$Model->alias] = $config + $this->settings[$Model->alias];
    }

    /**
     * @param Model $Model
     * @param bool  $created
     * @param array $options
     *
     * @throws Exception
     */
    public function afterSave(Model $Model, $created, $options = array())
    {
        $alias = $Model->alias;
        if ($alias != 'Log') {
            $data = self::_set_data($Model);
            $data = self::_set_data_by_save($created, $options, $data);
            self::_save_log($data);
        }
    }

    /**
     * @param Model $Model
     *
     * @throws Exception
     */
    public function afterDelete(Model $Model)
    {
        $alias = $Model->alias;
        if ($alias != 'Log') {
            $data = self::_set_data($Model);
            $data = self::_set_data_by_delete($data);
            self::_save_log($data);
        }
    }

    /**
     * @param Model $Model
     * @param       $data
     *
     * @return mixed
     */
    protected static function _get_data(Model $Model)
    {
        $data = array();
        $data['model_alias'] = $Model->alias;
        $data['model_data']  = json_encode($Model->data);

        return $data;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public static function _get_user($data, $userFields)
    {
        $userSession = CakeSession::read('Auth');
        if (!empty($userSession)) {

            $key = current(array_keys($userSession));
            $userData = array();

            foreach ($userSession[$key] as $userKey => $userValue) {
                if (in_array($userKey, $userFields)) {
                    $userData[$key][$userKey] = $userValue;
                }
            }

            $data['auth_user'] = json_encode($userData);

            return $data;
        }

        return null;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    protected static function _get_request($data)
    {
        $request = new CakeRequest();
        $data['request_user_agent'] = $request::header('User-Agent');
        $data['request_client_ip']  = $request->clientIp();
        $data['request_method']     = $request->method();
        $data['request_referer']    = $request->referer();
        $data['request_url']        = Router::url(null, true);

        return $data;
    }

    /**
     * @param Model $Model
     *
     * @return array|mixed
     */
    protected function _set_data(Model $Model)
    {
        $settings = $this->settings[$Model->alias];
        $data = self::_get_data($Model);
        $data = self::_get_user(
            $data,
            $settings['userFields']
        );
        $data = self::_get_request($data);

        return $data;
    }

    /**
     * @param $created
     * @param $options
     * @param $data
     *
     * @return mixed
     */
    protected function _set_data_by_save($created, $options, $data)
    {
        $data['model_options'] = json_encode($options);
        $data['model_action'] = ($created == true) ? 'create' : 'update';
        $data['model_deleted'] = false;

        return $data;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    protected function _set_data_by_delete($data)
    {
        $data['model_action'] = 'delete';

        return $data;
    }

    /**
     * @param $data
     *
     * @throws Exception
     */
    protected static function _save_log($data)
    {
        $log = new Log();
        $log->create($data);
        $log->save();
        $log->clear();
    }
}