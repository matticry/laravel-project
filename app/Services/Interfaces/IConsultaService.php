<?php

namespace App\Services\Interfaces;

interface IConsultaService
{
    public function getAllConsultas(array $filters = []);
    public function getConsultaById($id);
    public function getConsultasPorPaciente($pacienteId);
    public function createConsulta(array $data);
    public function updateConsulta($id, array $data);
    public function deleteConsulta($id);
}
