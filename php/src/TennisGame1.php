<?php

declare(strict_types=1);

namespace TennisGame;

class TennisGame1 implements TennisGame
{
    private const array SCORE_WORDS = [
        0 => 'Love',
        1 => 'Fifteen',
        2 => 'Thirty',
        3 => 'Forty',
    ];

    private const ALL_WORD = 'All';

    private const DEUCE_WORD = 'Deuce';

    private const ADVANTAGE_WORD = 'Advantage';

    private const WIN_WORD = 'Win for';

    private const WINNING_THRESHOLD = 4;

    private int $score1 = 0;

    private int $score2 = 0;

    public function __construct(
        private string $player1Name,
        private string $player2Name
    ) {
    }

    public function wonPoint(string $playerName): void
    {
        if ($playerName !== $this->player1Name && $playerName !== $this->player2Name) {
            throw new \InvalidArgumentException("Invalid player name: {$playerName}");
        }
        if ($playerName === $this->player1Name) {
            $this->score1++;
        } else {
            $this->score2++;
        }
    }

    public function getScore(): string
    {
        //branch 1: if the score is equal
        if ($this->isTied()) {
            return $this->formatTiedScore($this->score1);
        }

        // branch 2: if the score is not equal and both players have less than 4 points
        if ($this->isBeforeWinningThreshold()) {
            return $this->formatScore($this->score1, $this->score2);
        }

        // branch 3: if the score is not equal and at least one player has 4 or more points
        return $this->formatWinningScore($this->score1, $this->score2);
    }

    private function isTied(): bool
    {
        return $this->score1 === $this->score2;
    }

    private function isBeforeWinningThreshold(): bool
    {
        return $this->score1 < self::WINNING_THRESHOLD && $this->score2 < self::WINNING_THRESHOLD;
    }

    private function formatScore(int $score1, int $score2): string
    {
        $score1Word = self::SCORE_WORDS[$score1];
        $score2Word = self::SCORE_WORDS[$score2];
        return "{$score1Word}-{$score2Word}";
    }

    private function formatWinningScore(int $score1, int $score2): string
    {
        $advantagePlayer = $score1 > $score2 ? $this->player1Name : $this->player2Name;
        $advantageDifference = abs($score1 - $score2);
        return $advantageDifference === 1 ? self::ADVANTAGE_WORD . " {$advantagePlayer}" : self::WIN_WORD . " {$advantagePlayer}";
    }

    private function formatTiedScore(int $score): string
    {
        return match ($score) {
            0, 1, 2 => self::SCORE_WORDS[$score] . '-' . self::ALL_WORD,
            default => self::DEUCE_WORD,
        };
    }
}
