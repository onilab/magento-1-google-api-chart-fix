<?php

class Onilab_GoogleCharts_Block_Adminhtml_Dashboard_Tab_Amounts extends Onilab_GoogleCharts_Block_Adminhtml_Dashboard_Graph
{
    /**
     * Initialize object
     *
     * @return void
     */
    public function __construct()
    {
        $this->setHtmlId('amounts');
        parent::__construct();
    }

    /**
     * Prepare chart data
     *
     * @return void
     */
    protected function _prepareData()
    {
        $this->setDataHelperName('adminhtml/dashboard_order');
        $this->getDataHelper()->setParam('store', $this->getRequest()->getParam('store'));
        $this->getDataHelper()->setParam('website', $this->getRequest()->getParam('website'));
        $this->getDataHelper()->setParam('group', $this->getRequest()->getParam('group'));

        $this->setDataRows('revenue');
        $this->_axisMaps = array(
            'x' => 'range',
            'y' => 'revenue'
        );

        parent::_prepareData();
    }

    public function getTitle()
    {
        return $this->__('Amounts');
    }
}