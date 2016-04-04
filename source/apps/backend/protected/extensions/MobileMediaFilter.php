<?php
class MobileMediaFilter {
	
	const YOUTUBE_ID_LENGTH = '11';
	const ZING_ID_LENGTH = '8';
	const NHACCUATUI_ID_LENGTH = '12,13';
	
	public $zingFrame = '<a href="http://mp3.zing.vn/%s/zing/%s.html" target="_blank">http://mp3.zing.vn/%s/zing/%s.html</a>';
	public $nhaccuatuiFrame = '<a href="http://www.nhaccuatui.com/%s/nhaccuatui.%s.html" target="_blank">http://www.nhaccuatui.com/%s/nhaccuatui.%s.html</a>';
	public $youtubeFrame = '<a href="https://www.youtube.com/watch?v=%s" target="_blank">https://www.youtube.com/watch?v=%s</a>';
	
	public $filterYoutube = true;
	public $filterZing = true;
	public $filterNhaccuatui = true;
	public $filterLink = true;
	
	private $pattern = array();
	private $replacement = array();
	
	private $input = '';
	
	public function filter($input) {
		$this->input = $input;
		
		if($this->filterYoutube)
			$this->buildYoutubePattern();
		if($this->filterZing)
			$this->buildZingPattern();
		if($this->filterNhaccuatui)
			$this->buildNhaccuatuiPattern();
		
		if($this->filterLink) {
			$pattern = '/(^|\s)((https?:\/\/www.|https?:\/\/|www.)([^\s]*))/';
			$output = preg_replace_callback($pattern, array($this, 'callbackFilterLink'), $this->input);
		} else
			$output = $this->input;
		
		return $output;
	}
	private function buildYoutubePattern() {
		$pattern['yoututbeDirect'] = '/https?:\/\/www\.youtube\.com\/watch\?v=((.{11}).*&amp;list=.+|(.{11}))/';
		$pattern['yoututbeShare'] = '/http:\/\/youtu\.be\/(.+)/';
		$pattern['yoututbeEmbed'] = '/&lt;iframe.+\/\/www\.youtube\.com\/embed\/([^\s]*).+iframe&gt;/';
		$this->input = preg_replace_callback($pattern, array($this, 'callbackFilterYoutube'), $this->input);
	}
	private function buildZingPattern() {
		$pattern['zingDirect'] = '/http:\/\/mp3.zing.vn\/(bai-hat|album)\/.+\/(.{8}).html\S*/';
		$pattern['zingEmbedOld'] = '/&lt;object.+mp3.zing.vn\/embed\/(song|album)\/(.{8})[^\"].+br \/&gt;/';
		$pattern['zingEmbed'] = '/&lt;iframe.+http:\/\/mp3.zing.vn\/embed\/(song|album)\/(.{8}).+iframe&gt;/';
		
		$this->input = preg_replace_callback($pattern, array($this, 'callBackFilterZing'), $this->input);
	}
	private function buildNhaccuatuiPattern() {
		$pattern['nhaccuatuiDirect'] = '/http:\/\/www\.nhaccuatui\.com\/(bai-hat|playlist)\/.+\.(.{12,13})\.html\S*/';
		$pattern['nhaccuatuiEmbed'] = '/&lt;object.+nhaccuatui.com\/(l|m)\/([^&]*).+object&gt;/';
		
		$this->input = preg_replace_callback($pattern, array($this, 'callBackFilterNhaccuatui'), $this->input);
	}
	private function callBackFilterZing($matches) {
		$lastQuote = strrpos($matches['1'], '&quot;');
		if ($lastQuote !== false) {
			$matches['1'] = substr_replace($matches['1'], '', $lastQuote, 6);
		}
		$typeLink = ($matches['1'] == 'bai-hat' || $matches['1'] == 'song') ? 'bai-hat' : 'album';
		return sprintf($this->zingFrame, $typeLink, $matches['2'], $typeLink, $matches['2']);
	}
	private function callBackFilterNhaccuatui($matches) {
		$lastQuote = strrpos($matches['1'], '&quot;');
		if ($lastQuote !== false) {
			$matches['1'] = substr_replace($matches['1'], '', $lastQuote, 6);
		}
		$typeLink = ($matches['1'] == 'playlist' || $matches['1'] == 'l') ? 'playlist' : 'bai-hat';
		return sprintf($this->nhaccuatuiFrame, $typeLink, $matches['2'], $typeLink, $matches['2']);
	}
	private function callbackFilterYoutube($matches) {
		$lastQuote = strrpos($matches['1'], '&quot;');
		if ($lastQuote !== false) {
			$matches['1'] = substr_replace($matches['1'], '', $lastQuote, 6);
		}
		$pos = strpos($matches['1'], '?');
		if ($pos !== false) {
			$matches['1'] = substr_replace($matches['1'], '&', $pos, 1);
		}
		return sprintf($this->youtubeFrame, $matches['1'], $matches['1']);
	}
	private function callbackFilterLink($matches) {
		$link = ($matches[3] == 'www.') ? 'http://'.$matches[2] : $matches[2];
		return $matches[1].'<a href="'.$link.'" target="_blank">'.$matches[2].'</a>';
	} 
}