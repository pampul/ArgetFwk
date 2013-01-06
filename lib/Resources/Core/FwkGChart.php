<?php

/**
 * FwkGChart : Classe permettant de générer une nouvelle charte google
 *
 * @author f.mithieux
 */
class FwkGChart extends FwkManager {
    
    
    /**
     * div contenant la chart html
     * 
     * @var string
     */
    private $gChartHtml;
    
    /**
     * javascript contenant l'appel à la charte
     * 
     * @var string
     */
    private $gChartJs;
    
    /**
     * Nom de la GChart
     * 
     * @var string
     */
    private $gChartName;
    
    /**
     * Titre de la charte
     * 
     * @var string
     */
    private $gChartTitle;
    
    /**
     * Type de chart
     * 
     * @var string
     */
    private $gChartType;
    
    /**
     * Variables css de la charte (ex : 'background: white; float: left;')
     * 
     * @var string
     */
    private $gChartStyle;
    
    /**
     * Options de la charte Google
     * 
     * @var array $gChartOptions
     */
    private $gChartOptions;
    
    /**
     * Taille de la chart google
     *
     * @var array
     */
    private $gChartSize;
    
    /**
     * Instancie la classe FwkGChart
     * 
     * @param type $titleChart
     * @param type $nameGChart
     * @param type $typeChart
     * @param type $arrayOptions
     * @param type $styleDiv
     * @param type $arrayChartSize
     */
    public function __construct($titleChart = 'MyChart', $nameGChart = 'chart_div', $typeChart = 'PieChart', $arrayOptions = array(), $styleDiv = '', $arrayChartSize = array('width' => 400, 'height' => 300)) {
        
        $this->gChartTitle = $titleChart;
        $this->gChartName = $nameGChart;
        $this->gChartType = $typeChart;
        $this->gChartStyle = $styleDiv;
        $this->gChartOptions = $arrayOptions;
        $this->gChartSize = $arrayChartSize;
        
        $this->gChartHtml = $this->setGHtml();
        $this->gChartJs = $this->setGJs();
        
    }
    
    /**
     * Inclus le JS appelant la lib GChart
     * 
     * @return string
     */
    public function drawCallGChart(){
        return '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';
    }
    
    /**
     * Affiche la valeur de la div en html
     * 
     * @return string
     */
    public function drawHtml(){
        return $this->gChartHtml;
    }
    
    /**
     * Affiche le JS de la GChart
     * 
     * @return string
     */
    public function drawJs(){
        return $this->gChartJs;
    }
    
    /**
     * Construis le HTML
     * 
     */
    private function setGHtml(){
        return '<div id="'.$this->gChartName.'" style="'.$this->gChartStyle.'"></div>';
    }
    
    /**
     * Construis le JS
     * 
     */
    private function setGJs(){
        
        $jsInclude = '
            <script type="text/javascript">

                // Load the Visualization API and the piechart package.
                google.load(\'visualization\', \'1.0\', {\'packages\':[\'corechart\']});

                // Set a callback to run when the Google Visualization API is loaded.
                google.setOnLoadCallback('.$this->gChartName.');
                    
                function '.$this->gChartName.'() {
                ';
        
        switch($this->gChartType){
            
            case 'PieChart' :
                $jsInclude .= $this->executePieChart();
                break;
            
            case 'LineChart' :
                $jsInclude .= $this->executeLineChart();
                break;
            
            default :
                $this->gChartType = 'PieChart';
                $jsInclude .= $this->executePieChart();
                break;
            
        }
        
        $jsInclude .= '
            
                    // Instantiate and draw our chart, passing in some options.
                    var chart = new google.visualization.'.$this->gChartType.'(document.getElementById(\''.$this->gChartName.'\'));
                    chart.draw(data, options);
                }
            </script>
            ';
        
        return $jsInclude;
        
    }
    
    /**
     * Les options doivent imperativement ressembler à :
     * array('Fromage' => 15, 'Beurre' => 5)
     * 
     * @return string
     */
    private function executePieChart(){
        
        $jsCustom = '
                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn(\'string\', \'Topping\');
                data.addColumn(\'number\', \'Slices\');
                data.addRows([';
        
        $sizeOfOptions = sizeof($this->gChartOptions);
        $i = 0;
        foreach($this->gChartOptions as $keyOption => $valueOption){
            $i++;
            if($i === $sizeOfOptions){
                $jsCustom .= '[\''.$keyOption.'\', '.$valueOption.']';
            }else{
                $jsCustom .= '[\''.$keyOption.'\', '.$valueOption.'],';
            }
            
            
        }
        
        $jsCustom .= '
                ]);
                
                // Set chart options
                var options = {
                    \'title\':\''.$this->gChartTitle.'\',
                    \'width\': \''.$this->gChartSize['width'].'%\',
                    \'height\': \''.$this->gChartSize['height'].'%\',
                    \'is3D\': true,
                    \'backgroundColor\': { fill: "none" }
                };';
        
        return $jsCustom;
        
    }
    
    
    /**
     * Les options doivent imperativement ressembler à :
     * array('head' => array('Premier Trait', 'Second Trait'), 'body' => array(array(150, 250), array(145, 175)))
     * 
     * @return string
     */
    private function executeLineChart(){
        
        $jsCustom = '
                var data = google.visualization.arrayToDataTable([';
        
        $arrayHead = $this->gChartOptions['head'];
        $sizeOfHead = sizeof($arrayHead);
        $arrayBody = $this->gChartOptions['body'];
        $sizeOfBody = sizeof($arrayBody);
        $jsCustom .= '
                        [';
        $i = 0;
        foreach($arrayHead as $header){
            $i++;
            if($i === $sizeOfHead){
                $jsCustom .= '\''.$header.'\'';
            }else{
                $jsCustom .= '\''.$header.'\', ';
            }
        }
        $jsCustom .= '],';
        
        $i = 0;
        foreach($arrayBody as $arrayValues){
            $i++;
            if($i === $sizeOfBody){
                $jsCustom .= '
                        [';
                $sizeOfValues = sizeof($arrayValues);
                for($j = 0; $j < $sizeOfValues; $j++){
                    if($j === 0){
                        $jsCustom .= '\''.$arrayValues[$j].'\', ';
                    }elseif($j < $sizeOfValues-1){
                        $jsCustom .= $arrayValues[$j].', ';
                    }else{
                        $jsCustom .= $arrayValues[$j];
                    }
                }
                $jsCustom .= ']';
            }else{
                $jsCustom .= '
                        [';
                $sizeOfValues = sizeof($arrayValues);
                for($j = 0; $j < $sizeOfValues; $j++){
                    if($j === 0){
                        $jsCustom .= '\''.$arrayValues[$j].'\', ';
                    }elseif($j < $sizeOfValues-1){
                        $jsCustom .= $arrayValues[$j].', ';
                    }else{
                        $jsCustom .= $arrayValues[$j];
                    }
                }
                $jsCustom .= '], ';
            }
        }
        
        $jsCustom .= '
                ]);

                var options = {
                    \'title\': \''.$this->gChartTitle.'\',
                    \'width\': \''.$this->gChartSize['width'].'%\',
                    \'height\': \''.$this->gChartSize['height'].'%\',
                    \'backgroundColor\': { fill: "none" }
                };';
        
        return $jsCustom;
        
    }
    
    
    
    
}

?>
