<?php

/* Source: https://github.com/caradojo/trivia/tree/master/php */

function echoln($string)
{
	echo $string . "\n";
}

class Game
{
	var $players, $places, $purses, $inPenaltyBox,
		$popQuestions, $scienceQuestions, $sportsQuestions,
		$rockQuestions, $currentPlayer = 0, $isGettingOutOfPenaltyBox,
		$fifty = 50, $zero = 0, $one = 1, $two = 2, $three = 3, $four = 4, $five = 5, $six = 6, $seven = 7, $eight = 8, $nine = 9, $ten = 10,
		$eleven = 11, $twelve = 12,
		$string_pop = "Pop", $string_science = "Science",
		$string_sports = "Sports", $string_rock = "Rock", $string_question = "Question";

	function  __construct()
	{

		$this->players = array();
		$this->places = array(0);
		$this->purses  = array(0);
		$this->inPenaltyBox  = array(0);

		$this->popQuestions = array();
		$this->scienceQuestions = array();
		$this->sportsQuestions = array();
		$this->rockQuestions = array();

		for (
			$i = 0;
			$i < $this->fifty;
			$i++
		) {
			array_push($this->popQuestions, ($this->string_pop . $this->string_question . $i));
			array_push($this->scienceQuestions, ($this->string_science . $this->string_question . $i));
			array_push($this->sportsQuestions, ($this->string_sports . $this->string_question . $i));
			array_push($this->rockQuestions, $this->createRockQuestion($i));
		}
	}

	function createRockQuestion($index)
	{
		return $this->string_rock . $this->string_question . $index;
	}

	function isPlayable()
	{
		return ($this->howManyPlayers() >= $this->two);
	}

	function add($playerName)
	{
		array_push($this->players, $playerName);
		$this->places[$this->howManyPlayers()] = $this->zero;
		$this->purses[$this->howManyPlayers()] = $this->zero;
		$this->inPenaltyBox[$this->howManyPlayers()] = false;

		echoln($playerName . " was added");
		echoln("They are player number " . count($this->players));
		return true;
	}

	function howManyPlayers()
	{
		return count($this->players);
	}

	function  roll($roll)
	{
		echoln($this->players[$this->currentPlayer] . " is the current player");
		echoln("They have rolled a " . $roll);

		if (
			$this->inPenaltyBox[$this->currentPlayer]
		) {
			if (
				$roll % $this->two == $this->zero
			) {

				echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
				$this->isGettingOutOfPenaltyBox = false;
			} else {

				$this->isGettingOutOfPenaltyBox = true;

				echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
				$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
				if (
					$this->places[$this->currentPlayer] > $this->eleven
				) {
					$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - $this->twelve;

					echoln($this->players[$this->currentPlayer]
						. "'s new location is "
						. $this->places[$this->currentPlayer]);
					echoln("The category is " . $this->currentCategory());
					$this->askQuestion();
				}
			}
		} else {

			$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
			if (
				$this->places[$this->currentPlayer] > $this->eleven
			) {
				$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - $this->twelve;

				echoln($this->players[$this->currentPlayer]
					. "'s new location is "
					. $this->places[$this->currentPlayer]);
				echoln("The category is " . $this->currentCategory());
				$this->askQuestion();
			}
		}
	}

	function  askQuestion()
	{
		if (
			$this->currentCategory() == $this->string_pop
		) {
			echoln(array_shift($this->popQuestions));
		} else if (
			$this->currentCategory() == $this->string_science
		) {
			echoln(array_shift($this->scienceQuestions));
		} else if (
			$this->currentCategory() == $this->string_sports
		) {
			echoln(array_shift($this->sportsQuestions));
		} else if (
			$this->currentCategory() == $this->string_rock
		) {
			echoln(array_shift($this->rockQuestions));
		}
	}


	function currentCategory()
	{
		if (
			$this->places[$this->currentPlayer] == $this->zero
			||
			$this->places[$this->currentPlayer] == $this->four
			||
			$this->places[$this->currentPlayer] == $this->eight
		) {
			return $this->string_pop;
		}

		if (
			$this->places[$this->currentPlayer] == $this->one
			||
			$this->places[$this->currentPlayer] == $this->five
			||
			$this->places[$this->currentPlayer] == $this->nine
		) {
			return $this->string_science;
		}


		if (
			$this->places[$this->currentPlayer] == $this->two
			||
			$this->places[$this->currentPlayer] == $this->six
			||
			$this->places[$this->currentPlayer] == $this->ten
		) {
			return $this->string_sports;
		}
		return $this->string_rock;
	}

	function wasCorrectlyAnswered()
	{
		if (
			$this->inPenaltyBox[$this->currentPlayer]
		) {
			if (
				$this->isGettingOutOfPenaltyBox
			) {
				echoln("Answer was correct!!!!");
				$this->purses[$this->currentPlayer]++;
				echoln($this->players[$this->currentPlayer]
					. " now has "
					. $this->purses[$this->currentPlayer]
					. " Gold Coins.");

				$winner = $this->didPlayerWin();
				$this->currentPlayer++;
				if (
					$this->currentPlayer == count($this->players)
				) {
					$this->currentPlayer = $this->zero;
				}
				return $winner;
			} else {
				$this->currentPlayer++;
				if (
					$this->currentPlayer == count($this->players)
				) {
					$this->currentPlayer = $this->zero;
				}
				return true;
			}
		} else {

			echoln("Answer was corrent!!!!");
			$this->purses[$this->currentPlayer]++;
			echoln($this->players[$this->currentPlayer]
				. " now has "
				. $this->purses[$this->currentPlayer]
				. " Gold Coins.");

			$winner = $this->didPlayerWin();
			$this->currentPlayer++;
			if (
				$this->currentPlayer == count($this->players)
			) {
				$this->currentPlayer = $this->zero;
			}
			return $winner;
		}
	}

	function wrongAnswer()
	{
		echoln("Question was incorrectly answered");
		echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
		$this->inPenaltyBox[$this->currentPlayer] = true;
		$this->currentPlayer++;
		if (
			$this->currentPlayer == count($this->players)
		) {
			$this->currentPlayer = $this->zero;
		}
		return true;
	}


	function didPlayerWin()
	{
		return !($this->purses[$this->currentPlayer] == $this->six);
	}
}
