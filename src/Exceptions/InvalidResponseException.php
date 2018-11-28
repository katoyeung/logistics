<?php
namespace Kato\Logistics\Exceptions;

use Exception;
use InvalidArgumentException;

class InvalidResponseException extends InvalidArgumentException
{
    /**
     * The invalid source.
     *
     * @var string
     */
    private $source;

    /**
     * The invalid method.
     *
     * @var string
     */
    private $method;

    /**
     * The invalid query.
     *
     * @var mixed
     */
    private $query;

    /**
     * Constructor.
     *
     * @param string          $source
     * @param string          $method
     * @param mixed           $query
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($source, $method, $query, $code = 0, Exception $previous = null)
    {
        $this->source = $source;
        $this->method = $method;
        $this->query = $query;
        parent::__construct($source.' : '. \GuzzleHttp\json_encode($query) .' return invalid response', $code, $previous);
    }

    /**
     * Get the invalid source.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get the invalid method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the invalid query.
     *
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }
}
