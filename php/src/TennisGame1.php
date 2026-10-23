<?php

declare(strict_types=1);

namespace TennisGame;

use InvalidArgumentException;

final class TennisGame1 implements TennisGame
{
    private const array SCORE_WORDS = [
        0 => 'Love',
        1 => 'Fifteen',
        2 => 'Thirty',
        3 => 'Forty',
    ];

    private const string ALL_WORD = 'All';

    private const string DEUCE_WORD = 'Deuce';

    private const string ADVANTAGE_WORD = 'Advantage';

    private const string WIN_WORD = 'Win for';

    private const int WINNING_THRESHOLD = 4;

    private int $score1 = 0;

    private int $score2 = 0;

    public function __construct(
        private readonly string $player1Name,
        private readonly string $player2Name
    ) {
        if ($this->player1Name === $this->player2Name) {
            throw new InvalidArgumentException('Player names must be distinct.');
        }
    }

    public function wonPoint(string $playerName): void
    {
        if ($playerName === $this->player1Name) {
            $this->score1++;
            return;
        }

        if ($playerName === $this->player2Name) {
            $this->score2++;
            return;
        }

        throw new InvalidArgumentException("Invalid player name: {$playerName}");
    }

    public function getScore(): string
    {
        if ($this->isTied()) {
            return $this->formatTiedScore();
        }

        if ($this->isEndgame()) {
            return $this->formatAdvantageOrWin();
        }

        return $this->formatScore();
    }

    private function isTied(): bool
    {
        return $this->score1 === $this->score2;
    }

    private function isEndgame(): bool
    {
        return $this->score1 >= self::WINNING_THRESHOLD
            || $this->score2 >= self::WINNING_THRESHOLD;
    }

    private function formatScore(): string
    {
        $score1Word = self::SCORE_WORDS[$this->score1] ?? throw new InvalidArgumentException("Invalid score for player 1: {$this->score1}");
        $score2Word = self::SCORE_WORDS[$this->score2] ?? throw new InvalidArgumentException("Invalid score for player 2: {$this->score2}");

        return "{$score1Word}-{$score2Word}";
    }

    private function formatAdvantageOrWin(): string
    {
        $leader = $this->score1 > $this->score2 ? $this->player1Name : $this->player2Name;
        $difference = abs($this->score1 - $this->score2);

        return $difference === 1
            ? self::ADVANTAGE_WORD . " {$leader}"
            : self::WIN_WORD . " {$leader}";
    }

    private function formatTiedScore(): string
    {
        return match ($this->score1) {
            0, 1, 2 => self::SCORE_WORDS[$this->score1] . '-' . self::ALL_WORD,
            default => self::DEUCE_WORD,
        };
    }
}
