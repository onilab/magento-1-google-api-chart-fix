<?php

class Onilab_GoogleCharts_Block_Adminhtml_Dashboard_Graph extends Mage_Adminhtml_Block_Dashboard_Graph
{
//    protected $_width = '700';
//    protected $_height = '300';

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate($this->_getTabTemplate());
    }

    /**
     * Get tab template
     *
     * @return string
     */
    protected function _getTabTemplate()
    {
        return 'onilab/google_charts/dashboard/graph.phtml';
    }

    public function getChartData()
    {
        $this->_allSeries = $this->getRowsData($this->_dataRows);

        foreach ($this->_axisMaps as $axis => $attr) {
            $this->setAxisLabels($axis, $this->getRowsData($attr, true));
        }

        $timezoneLocal = Mage::app()->getStore()->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE);

        list ($dateStart, $dateEnd) = Mage::getResourceModel('reports/order_collection')
            ->getDateRange($this->getDataHelper()->getParam('period'), '', '', true);

        $dateStart->setTimezone($timezoneLocal);
        $dateEnd->setTimezone($timezoneLocal);

        $dates = array();
        $datas = array();

        while ($dateStart->compare($dateEnd) < 0) {
            switch ($this->getDataHelper()->getParam('period')) {
                case '24h':
                    $d = $dateStart->toString('yyyy-MM-dd HH:00');
                    $dateStart->addHour(1);
                    break;
                case '7d':
                case '1m':
                    $d = $dateStart->toString('yyyy-MM-dd');
                    $dateStart->addDay(1);
                    break;
                case '1y':
                case '2y':
                    $d = $dateStart->toString('yyyy-MM');
                    $dateStart->addMonth(1);
                    break;
            }
            foreach ($this->getAllSeries() as $index => $serie) {
                if (in_array($d, $this->_axisLabels['x'])) {
                    $datas[$index][] = (float)array_shift($this->_allSeries[$index]);
                } else {
                    $datas[$index][] = 0;
                }
            }
            $dates[] = strtotime($d);
        }

        return [$datas, $dates];
    }
}