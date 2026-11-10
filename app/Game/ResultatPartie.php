<?php

namespace App\Game;

/**
 * Résultat d'une partie rejouée par le GameEngine.
 */
final readonly class ResultatPartie
{
    public function __construct(
        public int $ticks,
        public bool $arrive,
        public int $remplissage,
        public int $capacite,
        public int $mouillureFinale,
        public bool $victoire,
    ) {}

    public function pourcentage(): float
    {
        return round($this->remplissage * 100 / $this->capacite, 2);
    }

    /**
     * @return array{ticks: int, arrive: bool, remplissage: int, capacite: int, pourcentage: float, mouillure_finale: int, victoire: bool}
     */
    public function toArray(): array
    {
        return [
            'ticks' => $this->ticks,
            'arrive' => $this->arrive,
            'remplissage' => $this->remplissage,
            'capacite' => $this->capacite,
            'pourcentage' => $this->pourcentage(),
            'mouillure_finale' => $this->mouillureFinale,
            'victoire' => $this->victoire,
        ];
    }
}
