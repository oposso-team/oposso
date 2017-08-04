<?php

/**
 * Pagination 
 * 
 * @author Alexander Rein <a.rein@be-clever-ag.de>, beclever werbeagentur AG <support@be-clever-ag.de>
 * @copyright (c) 2017, Alexander Rein
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 */
class Pagination {

	private $_resuilt;
	private $_limit;
	private $_page;
	private $_query;
	private $_total;

	public function __construct($result) {

		$this->_resuilt = $result;
		$this->_total = count($this->_resuilt);
	}

	public function getPart($limit = 100, $page = 1) {

		$this->_limit = $limit;
		$this->_page = $page;

		if ($this->_limit == 'all') {
			$part = $this->_resuilt;
		} else {
			$temp = array_chunk($this->_resuilt, $limit);
			if (isset($temp[$page - 1]) || array_key_exists($page - 1, $temp)) {
				$part = $temp[$page - 1];
			} else {
				$part = $page < 1 ? $temp[0] : end($temp);
			}
		}

		$result = new stdClass();
		$result->page = $this->_page;
		$result->limit = $this->_limit;
		$result->total = $this->_total;
		$result->part = $part;

		return $result;
	}

	public function createLinks($links = 5, $list_class = "pagination") {
		if ($this->_limit == 'all' || $this->_total <= $this->_limit) {
			return '';
		}

		$last = ceil($this->_total / $this->_limit);

		$start = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
		$end = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;

		$html = '<ul class="' . $list_class . '">';

		$class = ( $this->_page == 1 ) ? "disabled" : "";
		$html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . '">&laquo;</a></li>';

		if ($start > 1) {
			$html .= '<li><a href="?limit=' . $this->_limit . '&page=1">1</a></li>';
			$html .= '<li class="disabled"><span>...</span></li>';
		}

		for ($i = $start; $i <= $end; $i++) {
			$class = ( $this->_page == $i ) ? "active" : "";
			$html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a></li>';
		}

		if ($end < $last) {
			$html .= '<li class="disabled"><span>...</span></li>';
			$html .= '<li><a href="?limit=' . $this->_limit . '&page=' . $last . '">' . $last . '</a></li>';
		}

		$class = ( $this->_page == $last ) ? "disabled" : "";
		$html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';

		$html .= '</ul>';

		return $html;
	}

}
