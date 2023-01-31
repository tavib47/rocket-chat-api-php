<?php

namespace ATDev\RocketChat\Subscriptions;

/**
 * Subscription data trait
 */
trait Data
{

  /** @var string Room id */
  private $roomId;
  /** @var string Date-time */
  private $updatedAt;
  /** @var string Username */
  private $username;
  /** @var string Subscription name */
  private $name;
  /** @var string Subscription fname */
  private $fname;
  /** @var boolean Indicates if subscription is open */
  private $open;
  /** @var boolean Indicates if subscription alerts are on */
  private $alert;
  /** @var integer Number of unread messages */
  private $unread;
  /** @var integer Number of user mentions */
  private $userMentions;
  /** @var integer Number of group mentions */
  private $groupMentions;

  /**
   * Class constructor
   *
   * @param string $roomId
   */
  public function __construct($roomId = null) {
    if (!empty($roomId)) {
      $this->setRoomId($roomId);
    }
  }

  /**
   * Gets room id
   *
   * @return string
   */
  public function getRoomId()
  {
    return $this->roomId;
  }

  /**
   * Sets room id
   *
   * @param string $roomId
   *
   * @return \ATDev\RocketChat\Subscriptions\Data
   */
  public function setRoomId($roomId)
  {
    if (!is_string($roomId)) {
      $this->setDataError("Invalid room Id");
    } else {
      $this->roomId = $roomId;
    }

    return $this;
  }

  /**
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * @return string
   */
  public function getUpdatedAt()
  {
    return $this->updatedAt;
  }

  /**
   * @return string
   */
  public function getfName()
  {
    return $this->fname;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return boolean
   */
  public function getOpen()
  {
    return $this->open;
  }

  /**
   * @return boolean
   */
  public function getAlert()
  {
    return $this->alert;
  }

  /**
   * @return integer
   */
  public function getUnread()
  {
    return $this->unread;
  }

  /**
   * @return integer
   */
  public function getUserMentions()
  {
    return $this->userMentions;
  }

  /**
   * @return integer
   */
  public function getGroupMentions()
  {
    return $this->groupMentions;
  }

  /**
   * Creates subscription out of api response
   *
   * @param \stdClass $response
   *
   * @return \ATDev\RocketChat\Subscriptions\Data
   */
  public static function createOutOfResponse($response)
  {
    $sub = new static($response->_id);

    return $sub->updateOutOfResponse($response);
  }

  /**
   * Updates current subscription out of api response
   *
   * @param \stdClass $response
   * @return \ATDev\RocketChat\Subscriptions\Data
   */
  public function updateOutOfResponse($response)
  {
    if (isset($response->rid)) {
      $this->setRoomId($response->rid);
    }

    if (isset($response->_updatedAt)) {
      $this->updatedAt = $response->_updatedAt;
    }

    if (isset($response->u) && isset($response->u->username)) {
      $this->username = $response->u->username;
    }

    if (isset($response->name)) {
      $this->name = $response->name;
    }

    if (isset($response->fname)) {
      $this->fname = $response->fname;
    }

    if (isset($response->open)) {
      $this->open = $response->open;
    }

    if (isset($response->alert)) {
      $this->alert = $response->alert;
    }

    if (isset($response->unread)) {
      $this->unread = $response->unread;
    }

    if (isset($response->userMentions)) {
      $this->userMentions = $response->userMentions;
    }

    if (isset($response->groupMentions)) {
      $this->groupMentions = $response->groupMentions;
    }

    return $this;
  }

  /**
   * Sets data error
   *
   * @param string $error
   *
   * @return \ATDev\RocketChat\Subscriptions\Data
   */
  private function setDataError($error)
  {
    static::setError($error);

    return $this;
  }
}
