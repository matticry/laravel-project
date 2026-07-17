<?php

namespace App\Mail;

use App\Models\Consulta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HistorialClinicoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $consulta;

    public function __construct(Consulta $consulta)
    {
        $this->consulta = $consulta;
    }

    public function build(): self
    {
        $pdf = Pdf::loadView('historial.pdf', ['consulta' => $this->consulta])
            ->setPaper('letter');

        $nombrePaciente = $this->consulta->paciente->us_name . ' ' . $this->consulta->paciente->us_lastName;

        return $this->view('emails.historial-clinico')
            ->subject('Historial Clínico — ' . $nombrePaciente)
            ->attachData($pdf->output(), 'Historial_Clinico_' . str_replace(' ', '_', $nombrePaciente) . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
