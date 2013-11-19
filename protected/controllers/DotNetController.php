<?php

class DotNetController extends Controller {

    public function actionIndex() {
        $services = array(
            'BizOpportunityService',
            'OrderService',
            'WorkOrderService',
        );

        $this->render('index', array(
            'services' => $services,
        ));
    }

    /**
     * Service 方法列表
     * @param string $serviceName
     */
    public function actionFunctions($serviceName) {
        $this->render('functions', array(
            'serviceName' => $serviceName,
            'functions' => $this->getTypes($serviceName),
        ));
    }

    /**
     * jBoss ESB 代码生成
     * @param string $serviceName
     * @param string $methodName
     */
    public function actionEsb($serviceName, $methodName) {
        $this->render('esb', array(
            'serviceName' => $serviceName,
            'methodName' => $methodName,
            'types' => $this->getTypes($serviceName, $methodName),
        ));
    }

    /**
     * 获取 Web Service 数据
     * @param string $serviceName
     * @param string $methodName
     * @return array
     */
    protected function getTypes($serviceName, $methodName = null) {
        $client = new SoapClient('http://192.168.50.34:2013/' . $serviceName . '.asmx?wsdl', array('trace' => true, 'exceptions' => true));
        $rawTypes = $client->__getTypes();
        $types = array();
        foreach ($rawTypes as $type) {
            $typeString = explode('@', trim(strtr($type, array('struct' => '', '{' => '@', '}' => ''))));
            $rawParams = explode('; ', trim(str_replace("\n", '', trim($typeString[1]) . ' ')));
            $params = array();
            if (substr(trim($typeString[0]), -8) != 'Response') {
                foreach ($rawParams as $param) {
                    if (!empty($param)) {
                        $t = explode(' ', $param);
                        $params[str_replace(';', '', $t[1])] = $t[0];
                    }
                }
                $types[trim($typeString[0])] = $params;
            }
        }

        if (!empty($methodName)) {
            return $types[$methodName];
        } else {
            return $types;
        }
    }

}
