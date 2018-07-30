<?php
/**
 * Spider website class
 * 
 * PHP versions 5
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 *
 * @category   class
 * @package    Spider
 * @author     Karol Janyst (LapKom)
 * @copyright  2009 Karol Janyst
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License v3
 * @version    0.1
 **/
class Spider {
 /**
  * cURL connection handler
  * 
  * @var resource
  * @access private
  */
  private $curl_session;
 /**
  * Root url value
  * 
  * @var array
  * @access private
  */
  private $root_url = array(
                      'scheme' => 'http',
                      'host' => 'localhost',
                      'path' => '/');
 /**
  * All found links
  * 
  * @var array
  * @access public
  */
  public $all_links = array();
 /**
  * Allowed file types
  * 
  * @var array
  * @access private
  */
  private $accept_types = array('htm', 'html', 'php', 'php5', 'aspx');
 /**
  * Verbose spidering process
  * 
  * @var boolean
  * @access private
  */
  private $verbose = false;
 /**
  * Fetched urls
  * 
  * @var integer
  * @access private
  */
  private $fetched_urls = 0;
 /**
  * Not fetched urls
  * 
  * @var integer
  * @access private
  */
  private $not_fetched_urls = 0;
 /**
  * User agent string
  * 
  * @var string
  * @access private
  */
  private $user_agent = 'Spider website 0.1';
 /**
  * Constructor
  *
  * @param string $site as root url
  * @access public
  * @return void
  */
  public function __construct ($site = '') {
    $this->setRootURL($site);
    $this->curl_session = curl_init();
  }
 /**
  * Changes root url
  *
  * @param string $site as new root url
  * @access public
  * @return void
  */
  public function setRootURL($site) {
    if (!empty($site)) {
      $this->root_url = parse_url($site);
    }
  }
 /**
  * Allows file type being spidering
  *
  * @param string $extension
  * @access public
  * @return void
  */
  public function allowType($extension) {
    if (!empty($extension)) {
      if (!in_array($extension, $this->accept_types)) array_push($this->accept_types, $extension);
    }
  }
 /**
  * Restricts file type from being spidered
  *
  * @param string $extension
  * @access public
  * @return void
  */
  public function restrictType($extension) {
    if (!empty($extension) && in_array($extension, $this->accept_types)) {
      foreach ($this->accept_types as $key => $value) {
        if ($extension == $value) {
          $this->accept_types[$key] = null;
        }
      }
      $this->accept_types = array_filter($this->accept_types);
    }
  }
 /**
  * Checks if url allowed to be fetched
  *
  * @param string $url url of page, string $useragent as useragent string
  * @access private
  * @return boolean Returns true if url allowed to fetch and false if otherwise
  */
  private function _robotsAllowed ($url, $useragent=false) { 
    $parsed = parse_url($url);
    $agents = array(preg_quote('*'));
    if($useragent) {
      $agents[] = preg_quote($useragent);
    }
    $agents = implode('|', $agents);
    $robotstxt = @file('http://'.$parsed['host'].'/robots.txt');
    if(!$robotstxt) 
      return true;
    $rules = array();
    $ruleapplies = false;
    foreach($robotstxt as $line) {
      if(!$line = trim($line)) continue;
      if(preg_match('/User-agent: (.*)/i', $line, $match)) { 
        $ruleapplies = preg_match('/('.$agents.')/i', $match[1]);
      } 
      if($ruleapplies && preg_match('/Disallow:(.*)/i', $line, $regs)) { 
        if(!$regs[1]) return true;
        $rules[] = preg_quote(trim($regs[1]), '/');
      }
    }
    foreach($rules as $rule) {
      if(preg_match('/^'.$rule.'/', $parsed['path'])) return false;
    }
    return true; 
  } 
 /**
  * Fetches given url
  *
  * @access private
  * @return void
  */
  private function _fetchUrl($url) {
    
      $url_status = array();
      $url_status['url'] = $url;
      curl_setopt($this->curl_session, CURLOPT_URL, $url);
      curl_setopt($this->curl_session, CURLOPT_USERAGENT, $this->user_agent);
      curl_setopt($this->curl_session, CURLOPT_HEADER, 0);
      curl_setopt($this->curl_session, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($this->curl_session, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($this->curl_session, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($this->curl_session, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($this->curl_session, CURLOPT_POST, 0);
      $result = curl_exec($this->curl_session);
      $info = curl_getinfo($this->curl_session);
      return $info['http_code'];
  }
 /**
  * Starts spidering
  *
  * @access public
  * @return void
  */
  public function startSpider() {
    $url = $this->root_url['scheme'].'://'.$this->root_url['host'].$this->root_url['path'];
    if (!empty($this->root_url['query'])) {
        $url = $url.'?'.$this->root_url['query'];
    }
    return $this->_fetchUrl($url);
  }
}
?>