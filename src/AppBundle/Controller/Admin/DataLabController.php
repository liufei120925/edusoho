<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;

class DataLabController extends BaseController
{
    public function dataAction(Request $request)
    {
        $url = $this->getAppService()->getTokenLoginUrl('data_lab_esiframe', array());

        return $this->render('admin/data-lab/data.html.twig', array(
            'url' => $url,
        ));
    }

    public function setttingAction(Request $request)
    {
        return $this->render('admin/data-lab/setting.html.twig');
    }

    public function enableXapiSettingAction(Request $request)
    {
        if ('POST' == $request->getMethod()) {
            return $this->setXapiSetting(1);
        }

        return $this->render('admin/data-lab/open-setting.html.twig');
    }

    public function disableXapiSettingAction(Request $request)
    {
        if ('POST' == $request->getMethod()) {
            return $this->setXapiSetting(0);
        }

        return $this->render('admin/data-lab/close-setting.html.twig');
    }

    private function setXapiSetting($enable)
    {
        $xapiSdk = $this->getXapiService()->getXapiSdk();
        $xapiSdkValue = 0 === $enable ? false : true;
        $xapiSdk->setting('xapiUpload', $xapiSdkValue);

        $xapiSetting = $this->getSettingService()->get('xapi', array());
        $xapiSetting['enabled'] = $enable;
        $xapiSetting = $this->getSettingService()->set('xapi', $xapiSetting);

        return $this->createJsonResponse(array('success' => 1));
    }

    /**
     * @return SettingService
     */
    protected function getSettingService()
    {
        return $this->createService('System:SettingService');
    }

    /**
     * @return XapiService
     */
    protected function getXapiService()
    {
        return $this->createService('Xapi:XapiService');
    }

    protected function getAppService()
    {
        return $this->createService('CloudPlatform:AppService');
    }
}