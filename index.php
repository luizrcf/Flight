<?php

class Flight {


    private $flightNumber;
    private $cia;
    private $departureAirport;
    private $arrivalAirport;
    private $departureTime;
    private $arrivalTime;
    private $valorTotal;
    private $servicoBagagem;
    private $servicoCargaViva;

    public function __construct(
        string $flightNumber,
        string $cia,
        string $departureAirport,
        string $arrivalAirport,
        DateTime $departureTime,
        DateTime $arrivalTime,
        float $valorTotal,
        Servico $servicoBagagem,
        Servico $servicoCargaViva
    )
    {
        $this->flightNumber = $flightNumber;
        $this->cia = $cia;
        $this->departureAirport = $departureAirport;
        $this->arrivalAirport = $arrivalAirport;
        $this->departureTime = $departureTime;
        $this->arrivalTime = $arrivalTime;
        $this->valorTotal = $valorTotal;
        $this->servicoBagagem = $servicoBagagem;
        $this->$servicoCargaViva = $servicoCargaViva;
    }


    public function getFlightNumber()
    {
        return $this->flightNumber;
    }


    public function getCia()
    {
        return $this->cia;
    }

    public function getDepartureAirport()
    {
        return $this->departureAirport;
    }


    public function getArrivalAirport()
    {
        return $this->arrivalAirport;
    }


    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }

    public function getValorTotal()
    {
        return $this->valorTotal;
    }
   
    public function getServicoBagagem(){
        return $this->servicoBagagem;
    }
   
    public function getServicoCargaViva(){
        return $this->servicoCargaViva;
    }
}

class Servico {
    private $quantidade = 0;
    private $valorServico = 0;

    public function __construct(
        int $quantidade,
        float $valorServico
    )
    {
        $this->quantidade = $quantidade;
        $this->valorServico = $valorServico;
    }


    public function getQuantidade()
    {
        return $this->quantidade;
    }


    public function getValorServico()
    {
        return $this->valorServico;
    }

}

class Checkout
{
    private $flightOutbound;
    private $flightInbound;

    public function __construct(Flight $flightOutbound, Flight $flightInbound = null)
    {
        $this->flightOutbound = $flightOutbound;
        $this->flightInbound = $flightInbound;
    }

    public function generateExtract()
    {
        $flightDetailsOutbound = $this->getDetailsFlight($this->flightOutbound);
        $valorTotal = $flightDetailsOutbound['Valor'];

        $flightDetailsInbound = [];
        if (! is_null($this->flightInbound)) {
            $flightDetailsInbound = $this->getDetailsFlight($this->flightInbound);
            $valorTotal += $flightDetailsInbound['Valor'];
        }

        return (object) [
            'flightOutbound' => $flightDetailsOutbound,
            'flightInbound' => $flightDetailsInbound,
            'valorTotal' => $valorTotal
        ];
    }
   
    private function getDetailsFlight($flight){
        $valorTotalFlight += $flight->getValorTotal();
        $servicoBagagem = $flight->getServicoBagagem();
        $valorTotalFlight += $servicoBagagem->getValorServico();
        $servicoCargaViva = $flight->getServicoCargaViva();
        $valorTotalFlight += $servicoCargaViva->getValorServico();
           
        $flightDetails = [
            'De' => $flight->getDepartureAirport(),
            'Para' => $flight->getArrivalAirport(),
            'Embarque' => $flight->getDepartureTime()->format('d/m/Y H:i'),
            'Desembarque' => $flight->getArrivalTime()->format('d/m/Y H:i'),
            'Cia' => $flight->getCia(),
            'Valor' => $valorTotalFlight,
        ];
       
        if($servicoBagagem->getQuantidade() > 0){
            $flightDetails['Serviço de Bagagem'] = $servicoBagagem->getQuantidade();
            $flightDetails['Preço da Bagagem Adicional'] = $servicoBagagem->getValorServico();
        }
        if($servicoCargaViva->getQuantidade() > 0){
            $flightDetails['Serviço de Carga Viva'] = $servicoCargaViva->getQuantidade();
            $flightDetails['Preço do transporte de Carga Viva'] = $servicoCargaViva->getValorServico();
        }
       
        return $flightDetails;
    }
}
?>