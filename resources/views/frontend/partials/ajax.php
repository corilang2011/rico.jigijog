@php
    $provCodeID = $_POST['provCodeID'];

    $cities = DB::table('refprovince')->where('provCode', '')->pluck('title');

    $array = array();
    $count = 0;

    foreach($cities as $c){
        $array[$count++] = array('City' => ''.$c['citymunDesc']);
    }

    echo json_encode($array);
@endphp