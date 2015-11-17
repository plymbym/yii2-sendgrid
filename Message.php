<?php
/**
 * @author Bryan Jayson Tan <bryantan16@gmail.com>
 * @link http://bryantan.info
 * @date 3/24/14
 * @time 6:48 PM
 */

namespace shershennm\sendgrid;

use yii\mail\BaseMessage;
use yii\helpers\BaseArrayHelper;

class Message extends BaseMessage
{
	private $_sendGridMessage;

	public function getSendGridMessage()
	{
		if ($this->_sendGridMessage == null) {
			$this->_sendGridMessage = new \SendGrid\Email();
		}
		return $this->_sendGridMessage;
	}

	/**
	 * @inheritdoc
	 */
	public function getCharset()
	{
		// not available on sendgrid
	}

	/**
	 * @inheritdoc
	 */
	public function setCharset($charset)
	{
		// not available on sendgrid
	}

	/**
	 * @inheritdoc
	 */
	public function getFrom()
	{
		return $this->sendGridMessage->getFrom();
	}

	/**
	 * @inheritdoc
	 */
	public function setFrom($from)
	{
		$this->addEmailParam($from, 'from');

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getReplyTo()
	{
		return $this->sendGridMessage->getReplyTo();
	}

	/**
	 * @inheritdoc
	 */
	public function setReplyTo($replyTo)
	{
		$this->sendGridMessage->setReplyTo($replyTo);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getTo()
	{
		return $this->sendGridMessage->to;
	}

	/**
	 * @inheritdoc
	 */
	public function setTo($to)
	{
		$this->addEmailParam($to, 'to');

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getCc()
	{
		return $this->sendGridMessage->getCcs();
	}

	/**
	 * @inheritdoc
	 */
	public function setCc($cc)
	{
		$this->addEmailParam($cc, 'cc');

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getBcc()
	{
		return $this->sendGridMessage->getBccs();
	}

	/**
	 * @inheritdoc
	 */
	public function setBcc($bcc)
	{
		$this->addEmailParam($bcc, 'bcc');

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getSubject()
	{
		return $this->sendGridMessage->getSubject();
	}

	/**
	 * @inheritdoc
	 */
	public function setSubject($subject)
	{
		$this->sendGridMessage->setSubject($subject);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function setTextBody($text)
	{
		$this->sendGridMessage->setText($text);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function setHtmlBody($html)
	{
		$this->sendGridMessage->setHtml($html);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function attach($fileName, array $options = [])
	{
		$this->sendGridMessage->addAttachment($fileName);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function attachContent($content, array $options = [])
	{
		// no available method for sendgrid
	}

	/**
	 * @inheritdoc
	 */
	public function embed($fileName, array $options = [])
	{
		// no available method for sendgrid
	}

	/**
	 * @inheritdoc
	 */
	public function embedContent($content, array $options = [])
	{
		// no available method for sendgrid
	}

	/**
	 * @inheritdoc
	 */
	public function toString()
	{
		$string = '';
		foreach ($this->sendGridMessage->toWebFormat() as $key => $value) {
			$string .= sprintf("%s:%s\n", $key, $value);
		}
		return $string;
	}

	/**
	 * Adding to sendgrid params which coontains email new items
	 * @param string|array $paramValue ['email' => 'name'] or ['email', ['email' => 'name'], 'email'] or 'email'
	 * @inheritdoc yii\mail\MessageInterface for more info
	 * @param string $paramType sendGrid var name like cc, bcc, to, from
	 */
	private function addEmailParam($paramValue, $paramType)
	{
		$paramTypeName = $paramType . 'Name';

		$this->sendGridMessage->$paramType = [];
		$this->sendGridMessage->$paramTypeName = [];

		if (!is_array($paramValue) || BaseArrayHelper::isAssociative($paramValue)) {
			$this->addSingleParam($paramValue, $paramType);
		} else {
			foreach ($paramValue as $value) {
				$this->addSingleParam($paramValue, $paramType);
			}
		}

		return $this;
	}
	
	/**
	 * @param string|array $paramValue ['email' => 'name'] or 'email'
	 * @param string $paramType sendGrid var name like cc, bcc, to, from
	 */
	private function addSingleParam($paramValue, $paramType)
	{
		$addFunction = 'add' . ucfirst($paramType);

		if (is_array($paramValue) && BaseArrayHelper::isAssociative($paramValue)) {
			$this->sendGridMessage->$addFunction(key($paramValue), current($paramValue));
		}
		else
		{
			$this->$addFunction($paramValue);
		}
	}
} 
