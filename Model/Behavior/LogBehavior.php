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
    private $_defaultConfig = array(
        'userModels' => array(
            'Admin', 'Manager', 'User'
        ),
        'userFields' => array(
            'id', 'name', 'username', 'email'
        )
    );

    private $currentSettings;

    /**
     * @param Model $Model
     * @param array $config
     */
    public function setup(Model $Model, $config = array())
    {
        $this->settings[$Model->alias] = $this->_defaultConfig;
        $this->currentSettings = $this->settings[$Model->alias] = $config + $this->settings[$Model->alias];
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
            $data = $this->_set_data($Model);
            $data = $this->_set_data_by_save($created, $options, $data);
            $this->_save_log($data);
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
            $data = $this->_set_data($Model);
            $data = $this->_set_data_by_delete($data);
            $this->_save_log($data);
        }
    }

    /**
     * @param Model $Model
     * @param       $data
     *
     * @return mixed
     */
    private function _get_data(Model $Model)
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
    private function _get_user($data, $userFields)
    {
        $userSession = CakeSession::read('Auth');
        if (!empty($userSession)) {
            $userData = array();
            $userModels = $this->currentSettings['userModels'];
            foreach ($userModels as $userModel) {
                if (array_key_exists($userModel, $userSession)) {
                    foreach ($userSession[$userModel] as $userKey => $userValue) {
                        if (in_array($userKey, $userFields)) {
                            $userData[$userModel][$userKey] = $userValue;
                        }
                    }
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
    private function _get_request($data)
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
    private function _set_data(Model $Model)
    {
        $data = $this->_get_data($Model);
        $data = $this->_get_user(
            $data,
            $this->currentSettings['userFields']
        );
        $data = $this->_get_request($data);

        return $data;
    }

    /**
     * @param $created
     * @param $options
     * @param $data
     *
     * @return mixed
     */
    private function _set_data_by_save($created, $options, $data)
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
    private function _set_data_by_delete($data)
    {
        $data['model_action'] = 'delete';

        return $data;
    }

    /**
     * @param $data
     *
     * @throws Exception
     */
    private static function _save_log($data)
    {
        $log = new Log();
        $log->create($data);
        $log->save();
        $log->clear();
    }
}
