<?php

namespace FM\ClassificationBundle\Guesser;

use FM\ClassificationBundle\Collection\WeightedCollection;
use FM\ClassificationBundle\Normalizer\NormalizerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChainGuesser implements GuesserInterface
{
    /**
     * Results are weighted and summed
     */
    const MODE_CUMULATIVE = 'cumulative';

    /**
     * All guessers are weighted equal
     */
    const MODE_OR = 'or';

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolverInterface
     */
    protected $resolver;

    /**
     * @var array[<GuesserInterface, integer>]
     */
    protected $guessers;

    /**
     * @var null|array
     */
    protected $reportData;

    /**
     * Constructor.
     *
     * @param string                   $mode
     * @param OptionsResolverInterface $resolver
     */
    public function __construct($mode = self::MODE_CUMULATIVE, OptionsResolverInterface $resolver = null)
    {
        $this->mode = $mode;
        $this->resolver = $resolver;
    }

    /**
     * @param GuesserInterface    $guesser
     * @param int                 $boost
     * @param NormalizerInterface $inputNormalizer
     * @param NormalizerInterface $outputNormalizer
     * @return $this
     */
    public function addGuesser(GuesserInterface $guesser, $boost = 1,
        NormalizerInterface $inputNormalizer = null, NormalizerInterface $outputNormalizer = null
    ) {
        $this->guessers[] = [$guesser, $boost, $inputNormalizer, $outputNormalizer];

        return $this;
    }

    /**
     * @param  mixed              $value
     * @return WeightedCollection
     */
    public function guess($value)
    {
        $this->reportData = null;

        if ($this->resolver) {
            $value = $this->resolver->resolve($value);
        }

        switch ($this->mode) {
            case self::MODE_OR:
                return $this->guessOr($value);
                break;

            default:
            case self::MODE_CUMULATIVE:
                return $this->guessCumulative($value);
                break;
        }
    }

    /**
     * @param mixed $value
     * @return WeightedCollection
     */
    protected function guessCumulative($value)
    {
        $retval = null;

        $max = 0;
        foreach ($this->guessers as list($guesser, $boost, $inputNormalizer, $outputNormalizer)) {
            $max += (1 * $boost);
        }

        $baseWeight = 1 / $max;

        $results = new WeightedCollection();

        /** @var GuesserInterface $guesser */
        foreach ($this->guessers as list($guesser, $boost, $inputNormalizer, $outputNormalizer)) {
            $normalizedValue = $value;

            if ($inputNormalizer) {
                $normalizedValue = $inputNormalizer->normalize($value);
            }

            $guesses = $guesser->guess($normalizedValue);

            if ($outputNormalizer) {
                $guesses->map(function ($value) use ($outputNormalizer) {
                    return $outputNormalizer->normalize($value);
                });
            }

            $this->reportData[] = [
                'guesser' => get_class($guesser),
                'value' => json_encode($value),
                'topScore' => $guesses->topScore(),
                'top' => !is_object($guesses->top()) ? var_export($guesses->top(), true) : get_class($guesses->top()) . ':' . (string) $guesses->top(),
                'results_topScore' => $results->topScore(),
                'results_top' => !is_object($results->top()) ? var_export($results->top(), true) : get_class($results->top()) . ':' . (string) $results->top(),
                'max' => $max,
                'boost' => $boost,
                'baseWeight' => $baseWeight,
                'weight' => $baseWeight * $boost,
                'subreport' => ($guesser instanceof ChainGuesser ? $guesser->getReport() : null),
            ];

            $results->merge($guesses, $baseWeight * $boost);
        }

        return $results;
    }

    /**
     * @param mixed $value
     * @return WeightedCollection
     */
    protected function guessOr($value)
    {
        $retval = null;

        $max = 0;
        foreach ($this->guessers as list($guesser, $boost, $inputNormalizer, $outputNormalizer)) {
            if ($boost > $max) {
                $max = $boost;
            }
        }

        $results = new WeightedCollection();

        /** @var GuesserInterface $guesser */
        foreach ($this->guessers as list($guesser, $boost, $inputNormalizer, $outputNormalizer)) {
            $normalizedValue = $value;

            if ($inputNormalizer) {
                $normalizedValue = $inputNormalizer->normalize($value);
            }

            $guesses = $guesser->guess($normalizedValue);

            if ($outputNormalizer) {
                $guesses->map(function ($value) use ($outputNormalizer) {
                        return $outputNormalizer->normalize($value);
                    }
                );
            }

            $this->reportData[] = [
                'guesser' => get_class($guesser),
                'value' => json_encode($value),
                'topScore' => $guesses->topScore(),
                'top' => is_scalar($guesses->top()) ? var_export($guesses->top(), true) : get_class($guesses->top()) . ':' . (string) $guesses->top(),
                'boost' => $boost,
                'max' => $max,
                'weight' => $boost / $max,
                'subreport' => ($guesser instanceof ChainGuesser ? $guesser->getReport() : null),
            ];

            $results->merge($guesses, $boost / $max);
        }

        return $results;
    }

    /**
     * Returns collected report data
     *
     * @return array|null
     */
    public function getReport()
    {
        return $this->reportData;
    }
}
