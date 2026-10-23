<?php

declare(strict_types=1);

namespace TennisGame;

class TennisGame1 implements TennisGame
{
    private int $m_score1 = 0;

    private int $m_score2 = 0;

    public function __construct(
        private string $player1Name,
        private string $player2Name
    ) {}

    public function wonPoint(string $playerName): void
    {
        if ($playerName === 'player1') {
            $this->m_score1++;
        } else {
            $this->m_score2++;
        }
    }

    public function getScore(): string
    {
        //branch 1: if the score is equal
        if ($this->m_score1 === $this->m_score2) {
            return match ($this->m_score1) {
                0 => 'Love-All',
                1 => 'Fifteen-All',
                2 => 'Thirty-All',
                default => 'Deuce',
            };
        }

        // branch 2: if the score is not equal and either player has 4 or more points
        if ($this->m_score1 >= 4 || $this->m_score2 >= 4) {
            $advantagePlayer = $this->m_score1 > $this->m_score2 ? 'player1' : 'player2';
            $advantageDifference = abs($this->m_score1 - $this->m_score2);
            return $advantageDifference === 1 ? "Advantage {$advantagePlayer}" : "Win for {$advantagePlayer}";
        }

        // branch 3: if the score is not equal and both players have less than 4 points
        $scoreWordMap = [
            0 => 'Love',
            1 => 'Fifteen',
            2 => 'Thirty',
            3 => 'Forty',
        ];
        $score1Word = $scoreWordMap[$this->m_score1];
        $score2Word = $scoreWordMap[$this->m_score2];
        return "{$score1Word}-{$score2Word}";
    }
}
