<?php
namespace TravelSportsPro;

$result = array();

switch ($_POST['load']) {
    case 'programs':
        $result =  json_decode(json_encode(App::GetClient()->GetController('programs')->LoadPrograms($_POST)), true);
        break;
	case 'payment-plans':
        $result =  json_decode(json_encode(App::GetClient()->GetController('programs')->LoadPaymentPlans($_POST)), true);
        break;
    case 'teams':
        $result =  json_decode(json_encode(App::GetClient()->GetController('teams')->LoadTeams($_POST)), true);
        break;
    case 'divisions':
        $result =  json_decode(json_encode(App::GetClient()->GetController('divisions')->LoadDivisions($_POST)), true);
    break;
}

echo json_encode($result['data']);
