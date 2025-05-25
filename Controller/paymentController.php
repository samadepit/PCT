<?php

require_once __DIR__ . '/../Model/payment.php';

class PaymentController
{
    private $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new Payment();
    }

    public function createPayment(?string $code_demande = null, $numero,$code_paiement)
    {
        try {
            $result = $this->paymentModel->insert_paiement($code_demande, $numero, $code_paiement);
            if (!$result) {
                throw new Exception("Erreur lors de la création du paiement.");
            }
            return $code_paiement;
        } catch (Exception $e) {
            error_log("Erreur createPayment : " . $e->getMessage());
            return false;
        }
    }

    public function verifyPayment(string $numero, string $code_paiement)
    {
        try {
            $paiement = $this->paymentModel->verify_paiement($numero, $code_paiement);
            if (!$paiement) {
                throw new Exception("Paiement introuvable.");
            }
            return $paiement;
        } catch (Exception $e) {
            error_log("Erreur verifyPayment : " . $e->getMessage());
            return false;
        }
    }

    public function getPaymentByDemande(string $code_demande)
    {
        try {
            $paiement = $this->paymentModel->get_paiement_par_demand($code_demande);
            if (!$paiement) {
                throw new Exception("Aucun paiement trouvé pour cette demande.");
            }
            return $paiement;
        } catch (Exception $e) {
            error_log("Erreur getPaymentByDemande : " . $e->getMessage());
            return false;
        }
    }
}
