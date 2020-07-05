<?php
    class Covid{

        private $user_obj;
        private $con;

        public function __construct($con){
            $this->con = $con;
            // $this->user_obj = new User($con,$user);
            $this->data = "https://covid19api.io/api/v1/AllReports";
        }
        
        public function getCovidData(){
        $covid_json = file_get_contents($this->data);
        $covid_array = json_decode($covid_json,true);

        $str ="<div class = 'covid_table covid_title col-sm-12'> World data </div>
                <table class='table table-sm covid_table '><tbody>
                <tr>
                    <th class='text-left' scope='col'>Total Cases</th>
                    <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['TotalCases'] ."</td>
                </tr>
                <tr>
                    <th class='text-left' scope='col'>New Cases</th>
                    <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['NewCases'] ."</td>
                </tr>
                <tr>
                    <th class='text-left' scope='col'>Total Deaths</th>
                    <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['TotalDeaths'] ."</td>
                </tr>
                <tr>
                    <th class='text-left' scope='col'>New Deaths</th>
                    <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['NewDeaths'] ."</td>
                </tr>
                <tr>
                    <th class='text-left' scope='col'>Total Recovered</th>
                    <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['TotalRecovered'] ."</td>
                </tr>
                <tr>
                    <th class='text-left' scope='col'>New Recovered</th>
                    <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['NewRecovered'] ."</td>
                </tr>
                <tr>
                    <th class='text-left' scope='col'>Active Cases</th>
                    <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][0]['ActiveCases'] ."</td>
                </tr>
                <tbody></table>";
        return $str;
        }

        public function getCovidDataCountry($country){
            $covid_json = file_get_contents($this->data);
            $covid_array = json_decode($covid_json,true);
            $i=0;
            
            foreach($covid_array['reports'][0]['table'][0] as $covid){
                if ($covid['Country'] === $country){
                    $country = ucfirst($country);
                    $str ="<div class = 'covid_table covid_title col-sm-12'> $country data </div>
                    <table class='table table-sm covid_table '><tbody>
                    <tr>
                        <th class='text-left' scope='col'>Total Cases</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][$i]['TotalCases'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>New Cases</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][$i]['NewCases'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>Total Deaths</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][$i]['TotalDeaths'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>New Deaths</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][$i]['NewDeaths'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>Total Recovered</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][$i]['TotalRecovered'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>New Recovered</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][$i]['NewRecovered'] ."</td>
                    </tr>
                    <tr>
                        <th class='text-left' scope='col'>Active Cases</th>
                        <td class ='text-right' scope='col'>" . $covid_array['reports'][0]['table'][0][$i]['ActiveCases'] ."</td>
                    </tr>
                    <tbody></table>";
                    return $str;
                } else {
                    $i++;
                }
            }

            
        }

        public function getCovidCountries(){
            $list="";
            $covid_json = file_get_contents($this->data);
            $covid_array = json_decode($covid_json,true);
    
            foreach($covid_array['reports'][0]['table'][0] as $country){
                $list .= "<option>" . $country['Country'] . "</option>";
            }
            return $list;
        }
    } // end of class
?>