<?php
$xmlTemplate = <<<REQUEST
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema version="1.0" targetNamespace="http://www.jboss.org" xmlns:x1="http://www.jboss.org" xmlns:xs="http://www.w3.org/2001/XMLSchema" elementFormDefault="qualified">
    <xs:element name="Request" type="x1:Request" />
    <xs:complexType name="Request">
        <xs:sequence>
%s
        </xs:sequence>
    </xs:complexType>
</xs:schema>
REQUEST;
?>
<p>方法名称：<?php echo $methodName; ?></p>
<h1>Request XML</h1>
<?php
$params = $types;
$xml = array();
foreach ($params as $key => $value) {
    $xml[] = "          <xs:element name=\"{$key}\" type=\"xs:string\" minOccurs=\"1\" />";
}
echo '<textarea style="width: 100%; height: 200px;">' . sprintf($xmlTemplate, implode("\r\n", $xml)) . '</textarea>';
?>

<h1>Action</h1>
<?php
$actionTemplate = <<<ACTION
public Message %s(Message message) throws Exception {

    // 获取消息主体内容（包含客户端传递过来 JSON 变量）
    final String request = (String) message.getBody().get();

%s

    // 调用接口获取 JSON 对象
    %s service = new %s();
    %sSoap serviceSoap = service.get%sSoap();
    String response = serviceSoap.%s(%s);

    // 将结果添加到消息主体
    message.getBody().add(new XmlFormat().SetResponseValue(response));

    return message;
}
ACTION;
$request = array();
$paramString = array();
foreach ($params as $key => $value) {
    switch ($value) {
        case 'int':
            $request[] = "    int {$key} = Integer.parseInt(new XmlFormat().GetValueByRegexParse(request, \"{$key}\"));";
            break;
        case 'dateTime':
            $request[] = "    XMLGregorianCalendar {$key} = DatatypeFactory.newInstance().newXMLGregorianCalendar(new XmlFormat().GetValueByRegexParse(request, \"{$key}\"));";
            break;
        default :
            $request[] = "    String {$key} = new XmlFormat().GetValueByRegexParse(request, \"{$key}\");";
            break;
    }

    $paramString[] = $key;
}
echo '<textarea style="width: 100%; height: 600px;">' . sprintf($actionTemplate, lcfirst($methodName), implode("\r\n", $request), $serviceName, $serviceName, $serviceName, $serviceName, lcfirst($methodName), implode(", ", $paramString)) . '</textarea>';
