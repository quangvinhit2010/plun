<?php
class MediaFilter {
	
	const YOUTUBE_ID_LENGTH = '11';
	const ZING_ID_LENGTH = '8';
	const NHACCUATUI_ID_LENGTH = '12,13';
	
	public $zingFrame = '<div style="position: relative; overflow: hidden; height: %spx;"><iframe style="position: absolute; top: -20px;" width="406" height="320" src="http://mp3.zing.vn/embed/%s/%s}?autostart=false" frameborder="0" allowfullscreen="false"></iframe></div>';
	public $nhaccuatuiFrame = '<div style="height: %spx; position: relative; overflow: hidden;"><object style="position: absolute; top: -%spx;" width="271" height="428">
			<param name="movie" value="http://www.nhaccuatui.com/%s/%s" />
			<param name="quality" value="high" />
			<param name="wmode" value="transparent" />
			<param name="allowscriptaccess" value="always" />
			<param name="allowfullscreen" value="false"/>
			<param name="flashvars" value="autostart=false" />
			<embed src="http://www.nhaccuatui.com/%s/%s" flashvars="target=blank&autostart=false" 
				allowscriptaccess="always" allowfullscreen="false" quality="high" wmode="transparent" type="application/x-shockwave-flash" width="271" height="428"></embed>
			</object></div>';
	public $youtubeFrame = '<div style="width: 271px; height: 150px;"><iframe width="271" height="150" src="//www.youtube.com/embed/%s" frameborder="0" allowfullscreen></iframe></div>';
	
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
		$pattern['yoututbeEmbed'] = '/&lt;iframe.+\/\/www\.youtube\.com\/embed\/([^\s]*).+iframe&gt;/';
		$pattern['yoututbeDirect'] = '/https?:\/\/www\.youtube\.com\/watch\?v=((.{11}).*&amp;list=.+|(.{11}))/';
		$pattern['yoututbeShare'] = '/http:\/\/youtu\.be\/(.+)/';
		$this->input = preg_replace_callback($pattern, array($this, 'callbackFilterYoutube'), $this->input);
	}
	private function buildZingPattern() {
		$pattern['zingEmbed'] = '/&lt;iframe.+http:\/\/mp3.zing.vn\/embed\/(song|album)\/(.{8}).+iframe&gt;/';
		$pattern['zingEmbedOld'] = '/&lt;object.+mp3.zing.vn\/embed\/(song|album)\/(.{8})\?.+br \/&gt;/';
		$pattern['zingDirect'] = '/http:\/\/mp3.zing.vn\/(bai-hat|album)\/.+\/(.{8}).html\S*/';
		
		$this->input = preg_replace_callback($pattern, array($this, 'callBackFilterZing'), $this->input);
	}
	private function buildNhaccuatuiPattern() {
		$pattern['nhaccuatuiDirect'] = '/http:\/\/www\.nhaccuatui\.com\/(bai-hat|playlist)\/.+\.(.{12,13})\.html\S*/';
		$pattern['nhaccuatuiEmbed'] = '/&lt;object.+nhaccuatui\.com\/(l|m)\/(.{12,13})[&quot;].+object&gt;/';
		
		$this->input = preg_replace_callback($pattern, array($this, 'callBackFilterNhaccuatui'), $this->input);
	}
	private function callBackFilterZing($matches) {
		$lastQuote = strrpos($matches['1'], '&quot;');
		if ($lastQuote !== false) {
			$matches['1'] = substr_replace($matches['1'], '', $lastQuote, 6);
		}
		$typeLink = ($matches['1'] == 'bai-hat' || $matches['1'] == 'song') ? 'song' : 'album';
		$height = ($typeLink == 'song') ? 78 : 300;
		return sprintf($this->zingFrame, $height, $typeLink, $matches['2']);
	}
	private function callBackFilterNhaccuatui($matches) {
		$lastQuote = strrpos($matches['1'], '&quot;');
		if ($lastQuote !== false) {
			$matches['1'] = substr_replace($matches['1'], '', $lastQuote, 6);
		}
		$typeLink = ($matches['1'] == 'playlist' || $matches['1'] == 'l') ? 'l' : 'm';
		$height = ($typeLink == 'l') ? 185 : 34;
		$top = ($typeLink == 'l') ? 244 : 225;
		return sprintf($this->nhaccuatuiFrame, $height, $top, $typeLink, $matches['2'], $typeLink, $matches['2']);
	}
	private function callbackFilterYoutube($matches) {
		$lastQuote = strrpos($matches['1'], '&quot;');
		if ($lastQuote !== false) {
			$matches['1'] = substr_replace($matches['1'], '', $lastQuote, 6);
		}
		$pos = strpos($matches['1'], '&');
		if ($pos !== false) {
		    $matches['1'] = substr_replace($matches['1'], '?', $pos, 1);
		}
		return sprintf($this->youtubeFrame, $matches['1']);
	}
	private function callbackFilterLink($matches) {
		$link = ($matches[3] == 'www.') ? 'http://'.$matches[2] : $matches[2];
		return $matches[1].'<a href="'.$link.'" target="_blank">'.$matches[2].'</a>';
	}
}