<?php
/**
 * Created by PhpStorm.
 * User: Steven
 * Date: 26/01/2017
 * Time: 17:14
 */

namespace coolracing\controllers;

use coolracing\models\Epreuve;
use coolracing\models\Participant;
use coolracing\models\Participe;
use Dompdf\Dompdf;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Controller des participants
 * Class ParcitipantsController
 * @package coolracing\controllers
 */
class ParcitipantsController extends BaseController
{

    /**
     * Fonction de rendu du fichier de participants
     * Fichier au format XLSX ou PDF
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return static
     */
    public function participations(RequestInterface $request, ResponseInterface $response, $args)
    {
        $epreuve = Epreuve::where('idEpreuve', '=', $args['numepreuve'])->first();
        if (!is_null($epreuve)) {
            if ($args['type'] == 'pdf') {
                $dompdf = new Dompdf();
                $dompdf->loadHtml($this->generateHTML($epreuve));
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                $dompdf->stream('Participants_' . ucfirst($epreuve->nom));
                return $this->redirect($response, 'index');
            } else if ($args['type'] == 'xlsx') {
                $header = array(
                    'Nom' => 'string',
                    'Prenom' => 'string',
                    'Dosard' => 'string'
                );
                $writer = new \XLSXWriter();
                $writer->setAuthor('CoolRacing');
                $writer->setTitle('Participations ' . ucfirst($epreuve->nom));
                $data = $this->generateXLSX($epreuve);
                $writer->writeSheet($data, 'Participations_' . ucfirst($epreuve->nom), $header);
                $writer->writeToStdOut('Participations_' . ucfirst($epreuve->nom));
                return $this->redirect($response, 'index');
            } else {
                $this->flash('info', "Impossible de générer le document.");
                return $this->redirect($response, 'index');
            }
        } else {
            $this->flash('info', "Impossible de trouver l'épreuve.");
            return $this->redirect($response, 'index');
        }
    }

    /**
     * Fonction de generation du table pour le fichier XLSX
     * @param $epreuve
     * @return array
     */
    private function generateXLSX($epreuve) {
        $data = array();
        $participants = Participe::where('idEpreuve', '=', $epreuve->idEpreuve)->get();
        foreach ($participants as $participant) {
            $part = Participant::where('idParticipant', '=', $participant->idParticipant)->first();
            $data[] = array(
                $part->nom,
                $part->prenom,
                ''
            );
        }
        return $data;
    }

    /**
     * Fonction de generation du code HTML pour le fichier PDF
     * @param $epreuve
     * @return string
     */
    private function generateHTML($epreuve)
    {
        $participants = Participe::where('idEpreuve', '=', $epreuve->idEpreuve)->get();
        $html ='
        <h1>' . ucfirst($epreuve->nom) . '</h1>
        <h3>' . ucfirst($epreuve->desc) . '</h3>
        <p>Début : ' . $epreuve->dateDeb . '</p>
        <p>Fin : ' . $epreuve->dateFin . '</p>
        <table style="border-collapse: collapse; width: 100%; text-align: left;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 15px;">Nom</th>
                    <th style="border: 1px solid #ddd; padding: 15px;">Prenom</th>
                    <th style="border: 1px solid #ddd; padding: 15px;">Dossard</th>
                </tr>
            <tbody>';
        foreach ($participants as $participant) {
            $part = Participant::where('idParticipant', '=', $participant->idParticipant)->first();
            $html .= '
                <tr style="padding: 15px; border: 1px solid #ddd;">
                    <td style="border: 1px solid #ddd; padding: 15px;">' . $part->nom . '</td>
                    <td style="border: 1px solid #ddd; padding: 15px;">' . $part->prenom . '</td>
                    <td style="border: 1px solid #ddd; padding: 15px;">' . $part->dossard . '</td>
                </tr>';
        }
        $html .='
                <tr>
                    <td>&nbsp;</td>
                    <td style="border: 1px solid #ddd; padding: 15px; text-align: right;">Participations</td>
                    <td style="border: 1px solid #ddd; padding: 15px;">' . count($participants) . '</td>
                </tr>
            </tbody>
        </table>';
        return $html;
    }

}