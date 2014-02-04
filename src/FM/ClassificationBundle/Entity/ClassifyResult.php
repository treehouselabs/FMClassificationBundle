<?php

namespace FM\ClassificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Model to persist classification results
 * 
 * @ORM\Entity()
 * @ORM\Table()
 */
class ClassifyResult
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $classifier;

    /**
     * Data that went into the classifier
     *
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    protected $input;

    /**
     * Output label
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $output;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    protected $score;

    /**
     * Expected output label (used to train the system)
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $expected;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $hash;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->updateHash();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set input
     *
     * @param array $input
     * @return ClassifyResult
     */
    public function setInput($input)
    {
        $this->input = $input;

        $this->updateHash();

        return $this;
    }

    /**
     * Get input
     *
     * @return array 
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set output
     *
     * @param string $output
     * @return ClassifyResult
     */
    public function setOutput($output)
    {
        $this->output = $output;

        $this->updateHash();

        return $this;
    }

    /**
     * Get output
     *
     * @return string 
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set score
     *
     * @param float $score
     * @return ClassifyResult
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return float 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set expected
     *
     * @param string $expected
     * @return ClassifyResult
     */
    public function setExpected($expected)
    {
        $this->expected = $expected;

        return $this;
    }

    /**
     * Get expected
     *
     * @return string 
     */
    public function getExpected()
    {
        return $this->expected;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Updates md5 hash
     */
    protected function updateHash()
    {
        $this->hash = md5(serialize($this->input) . serialize($this->output) . $this->classifier);
    }

    /**
     * Set classifier
     *
     * @param string $classifier
     * @return ClassifyResult
     */
    public function setClassifier($classifier)
    {
        $this->classifier = $classifier;

        $this->updateHash();

        return $this;
    }

    /**
     * Get classifier
     *
     * @return string 
     */
    public function getClassifier()
    {
        return $this->classifier;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return ClassifyResult
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }
}
