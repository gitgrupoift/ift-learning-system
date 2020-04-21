<?php
/**
 * User
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Zoom API
 *
 * API Description
 *
 * OpenAPI spec version: 2.0.0
 * Contact: developer@zoom.us
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 2.4.8
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Model;

use \ArrayAccess;
use \Swagger\Client\ObjectSerializer;

/**
 * User Class Doc Comment
 *
 * @category Class
 * @description The user object represents a User on Zoom
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class User implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'User';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'type' => 'int',
        'pmi' => 'string',
        'timezone' => 'string',
        'dept' => 'string',
        'created_at' => '\DateTime',
        'last_login_time' => '\DateTime',
        'last_client_version' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'type' => null,
        'pmi' => null,
        'timezone' => null,
        'dept' => null,
        'created_at' => 'date-time',
        'last_login_time' => 'date-time',
        'last_client_version' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        'email' => 'email',
        'type' => 'type',
        'pmi' => 'pmi',
        'timezone' => 'timezone',
        'dept' => 'dept',
        'created_at' => 'created_at',
        'last_login_time' => 'last_login_time',
        'last_client_version' => 'last_client_version'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'first_name' => 'setFirstName',
        'last_name' => 'setLastName',
        'email' => 'setEmail',
        'type' => 'setType',
        'pmi' => 'setPmi',
        'timezone' => 'setTimezone',
        'dept' => 'setDept',
        'created_at' => 'setCreatedAt',
        'last_login_time' => 'setLastLoginTime',
        'last_client_version' => 'setLastClientVersion'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'first_name' => 'getFirstName',
        'last_name' => 'getLastName',
        'email' => 'getEmail',
        'type' => 'getType',
        'pmi' => 'getPmi',
        'timezone' => 'getTimezone',
        'dept' => 'getDept',
        'created_at' => 'getCreatedAt',
        'last_login_time' => 'getLastLoginTime',
        'last_client_version' => 'getLastClientVersion'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    

    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['first_name'] = isset($data['first_name']) ? $data['first_name'] : null;
        $this->container['last_name'] = isset($data['last_name']) ? $data['last_name'] : null;
        $this->container['email'] = isset($data['email']) ? $data['email'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['pmi'] = isset($data['pmi']) ? $data['pmi'] : null;
        $this->container['timezone'] = isset($data['timezone']) ? $data['timezone'] : null;
        $this->container['dept'] = isset($data['dept']) ? $data['dept'] : null;
        $this->container['created_at'] = isset($data['created_at']) ? $data['created_at'] : null;
        $this->container['last_login_time'] = isset($data['last_login_time']) ? $data['last_login_time'] : null;
        $this->container['last_client_version'] = isset($data['last_client_version']) ? $data['last_client_version'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (!is_null($this->container['first_name']) && (mb_strlen($this->container['first_name']) > 64)) {
            $invalidProperties[] = "invalid value for 'first_name', the character length must be smaller than or equal to 64.";
        }

        if (!is_null($this->container['last_name']) && (mb_strlen($this->container['last_name']) > 64)) {
            $invalidProperties[] = "invalid value for 'last_name', the character length must be smaller than or equal to 64.";
        }

        if ($this->container['email'] === null) {
            $invalidProperties[] = "'email' can't be null";
        }
        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->container['first_name'];
    }

    /**
     * Sets first_name
     *
     * @param string $first_name User's first name
     *
     * @return $this
     */
    public function setFirstName($first_name)
    {
        if (!is_null($first_name) && (mb_strlen($first_name) > 64)) {
            throw new \InvalidArgumentException('invalid length for $first_name when calling User., must be smaller than or equal to 64.');
        }

        $this->container['first_name'] = $first_name;

        return $this;
    }

    /**
     * Gets last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->container['last_name'];
    }

    /**
     * Sets last_name
     *
     * @param string $last_name User's last name
     *
     * @return $this
     */
    public function setLastName($last_name)
    {
        if (!is_null($last_name) && (mb_strlen($last_name) > 64)) {
            throw new \InvalidArgumentException('invalid length for $last_name when calling User., must be smaller than or equal to 64.');
        }

        $this->container['last_name'] = $last_name;

        return $this;
    }

    /**
     * Gets email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->container['email'];
    }

    /**
     * Sets email
     *
     * @param string $email User's email address
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->container['email'] = $email;

        return $this;
    }

    /**
     * Gets type
     *
     * @return int
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param int $type User's type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets pmi
     *
     * @return string
     */
    public function getPmi()
    {
        return $this->container['pmi'];
    }

    /**
     * Sets pmi
     *
     * @param string $pmi Personal Meeting ID
     *
     * @return $this
     */
    public function setPmi($pmi)
    {
        $this->container['pmi'] = $pmi;

        return $this;
    }

    /**
     * Gets timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->container['timezone'];
    }

    /**
     * Sets timezone
     *
     * @param string $timezone Time Zone
     *
     * @return $this
     */
    public function setTimezone($timezone)
    {
        $this->container['timezone'] = $timezone;

        return $this;
    }

    /**
     * Gets dept
     *
     * @return string
     */
    public function getDept()
    {
        return $this->container['dept'];
    }

    /**
     * Sets dept
     *
     * @param string $dept Department
     *
     * @return $this
     */
    public function setDept($dept)
    {
        $this->container['dept'] = $dept;

        return $this;
    }

    /**
     * Gets created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->container['created_at'];
    }

    /**
     * Sets created_at
     *
     * @param \DateTime $created_at User create time
     *
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->container['created_at'] = $created_at;

        return $this;
    }

    /**
     * Gets last_login_time
     *
     * @return \DateTime
     */
    public function getLastLoginTime()
    {
        return $this->container['last_login_time'];
    }

    /**
     * Sets last_login_time
     *
     * @param \DateTime $last_login_time User last login time
     *
     * @return $this
     */
    public function setLastLoginTime($last_login_time)
    {
        $this->container['last_login_time'] = $last_login_time;

        return $this;
    }

    /**
     * Gets last_client_version
     *
     * @return string
     */
    public function getLastClientVersion()
    {
        return $this->container['last_client_version'];
    }

    /**
     * Sets last_client_version
     *
     * @param string $last_client_version User last login client version
     *
     * @return $this
     */
    public function setLastClientVersion($last_client_version)
    {
        $this->container['last_client_version'] = $last_client_version;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


