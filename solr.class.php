<?php

class Solr {

	private $_host = "http://192.168.***";
	private $_port = "89**";
	private $_urlPair = "/***"; 
	private $_q = null;	
	private $_version = null;
	private $_start = null;
	private $_rows = null;
	private $_indent = null;
	private $_facet = null;
	private $_facetPivot = null;
	private $_group = null;
	private $_groupField = null;
	private $_facetField = null;
	private $_fSiteFacetNumFacetTerms = null;
	private $_facetMinCount = null;
	private $_facetLimit = null;
	private $_facetSort = null;
	private $_outputType = null;
	private $_fq = null;
	private $_fl = null;
	private $_url = null;		
	private $_arrParams = null;
	private $_paramIndex = 0;
	 
	private $strQ = 'q';
	private $strDefault = 'default';
	private $strVersion = 'version';
	private $strStart = 'start';
	private $strRows = 'rows';
	private $strIndent = 'indent';
	private $strFacet = 'facet';
	private $strFacetField = 'facet.field';
	private $strGroup = 'group';
	private $strGroupField = 'group.field';
	private $strFq = 'fq';
	private $strFl = 'fl';
	private $strOutputType = 'wt';
	private $strFSiteFacetNumFacetTerms = 'f.site.facet.numFacetTerms';
	private $strFacetMinCount = 'facet.mincount';
	private $strFacetLimit = 'facet.limit';
	private $strFacetSort = 'facet.sort';
	private $strFacetPivot = 'facet.pivot';
	
	private $_solrOutput;
	private $_allResults;
	
	private $isResultArray = FALSE;
	
	public function createUrl() 
	{
		$this->_url = $this->_host . ':' . $this->_port . $this->_urlPair;
		
		if(is_array($this->_arrParams) && count($this->_arrParams) > 0) 
		{			
			$i = 0;
			foreach ($this->_arrParams as $key)	
			{
				
				if($i > 0) {
					$this->_url .= '&';
				} 
				
				foreach ($key as $k => $v) {
					$this->_url .= $k . '=' . $v;
						
				}
				
				$i++;
			}
		}
		
	}
	
	/*
	 * 
	 * setters
	 * 
	 * */
	
	private function setParams($key, $value)
	{
		if(is_array($value) && count($value) > 0) 
		{

			foreach($value as $val) 
			{
				$this->_arrParams[$this->_paramIndex][$key] = $val;
				$this->_paramIndex++;
			}
			
		} 
		else 
		{		
			
			$this->_arrParams[$this->_paramIndex][$key] = $value;
			$this->_paramIndex++;
			
		}
	}
	
	public function run()
	{
		$this->_solrOutput = file_get_contents($this->_url);
	
		if('json' == $this->_outputType) 
		{
			$this->_allResults = json_decode($this->_solrOutput, $this->isResultArray);
		} 
		else 
		{
			//TODO: xml output için gerekli parser eklenecek
		}
	}
	
	public function setHost($host)
	{
		$this->_host = $host;
	}
	
	public function setPort($port)
	{
		$this->_port = $port;
	}
	
	public function setQ($q)
	{
		$this->_q = $q;
		$this->setParams($this->strQ, $this->_q);
	}
	
	public function setFacetSort($facetSort)
	{
		$this->_facetSort = $facetSort;
		$this->setParams($this->strFacetSort, $this->_facetSort);
	}
	
	public function setFacetLimit($facetLimit)
	{
		$this->_facetLimit = $facetLimit;
		$this->setParams($this->strFacetLimit, $this->_facetLimit);
	}
	
	public function setFacetMinCount($facetMinCount)
	{
		$this->_facetMinCount = $facetMinCount;
		$this->setParams($this->strFacetMinCount, $this->_facetMinCount);
	}
	
	public function setFSiteFacetNumFacetTerms($fSiteFacetNumFacetTerms)
	{
		$this->_fSiteFacetNumFacetTerms = $fSiteFacetNumFacetTerms;
		$this->setParams($this->strFSiteFacetNumFacetTerms, $this->_fSiteFacetNumFacetTerms);
	}
	
	public function setVersion($version) 
	{				
		$this->_version = $version;
		$this->setParams($this->strVersion, $this->_version);		
	}
	
	public function setStart($start) 
	{		
		$this->_start = $start;
		$this->setParams($this->strStart, $this->_start);		
	}
	
	public function setRows($rows) 
	{	
		$this->_rows = $rows;
		$this->setParams($this->strRows, $this->_rows);		
	}
	
	public function setIndent($indent) 
	{		
		$this->_indent = $indent;
		$this->setParams($this->strIndent, $this->_indent);		
	}
	
	public function setFacet($facet) 
	{		
		$this->_facet = $facet;
		$this->setParams($this->strFacet, $this->_facet);		
	}
	
	public function setGroup($group)
	{
		$this->_group = $group;
		$this->setParams($this->strGroup, $this->_group);
	}
	
	public function setFacetField($facetField) 
	{		
		$this->_facetField = $facetField;
		$this->setParams($this->strFacetField, $this->_facetField);
	}
	
	public function setFacetPivot($facetPivot)
	{
		$this->_facetPivot = $facetPivot;
		$this->setParams($this->strFacetPivot, $this->_facetPivot);
	}
	
	public function setGroupField($groupField)
	{
		$this->_groupField = $groupField;
		$this->setParams($this->strGroupField, $this->_groupField);
	}
	
	public function setOutputType($outputType) 
	{		
		$this->_outputType = $outputType;
		$this->setParams($this->strOutputType, $this->_outputType);		
	}
	
	public function setFq($fq) 
	{			
		$tempFq = '';	
		if(is_array($fq) && count($fq) > 0) 
		{
			$i = 1;
			$valCount = count($fq['values']);
			
			foreach($fq['values'] as $v)
			{					
				$tempFq .= $fq['field'] . ':' . '%22' . urlencode($v) . '%22';
				
				if($i < $valCount) 
				{
					$tempFq .= '%20' . $fq['where_type'] . '%20';	
				}	

				$i++;
			}
			
		}
		
		$this->_fq = $tempFq;
		$this->setParams($this->strFq, $this->_fq);		

	}
	
	public function setFl($fl) 
	{						
		$this->_fl = $fl;
		$this->setParams($this->strFl, $this->_fl);				
	}
	
	/*
	 * getters
	 * 
	 * */
	
	public function getResponse()
	{
		if($this->isResultArray === FALSE)
			return $this->_allResults->response;
		else 
			return $this->_allResults['response'];
	}
	
	public function getGrouped()
	{
		if($this->isResultArray === FALSE)
			return $this->_allResults->grouped;
		else 
			return $this->_allResults['grouped'];
	}
	
	public function getNumFound()
	{
		if($this->isResultArray === FALSE)
			return $this->_allResults->response->numFound;
		else 
			return $this->_allResults['response']['numFound'];
	}
	
	public function getDocs()
	{
		if($this->isResultArray === FALSE)
			return $this->_allResults->response->docs;
		else 
			return $this->_allResults['response']['docs'];
	}
	
	public function getFacetCounts()
	{
		if($this->isResultArray === FALSE)
			return $this->_allResults->facet_counts;
		else 
			return $this->_allResults['facet_counts'];
	}
	
	public function getFacetFields()
	{
		if($this->isResultArray === FALSE)
			return $this->_allResults->facet_counts->facet_fields;
		else 
			return $this->_allResults['facet_counts']['facet_fields'];
	}
	
	public function getHighlighting()
	{
		if($this->isResultArray === FALSE)
			return $this->_allResults->highlighting;
		else
			return $this->_allResults['highlighting'];
	}
	
	public function getAllResults()
	{
		return $this->_allResults;
	}
	
	public function getSolrOutput()
	{
		return $this->_solrOutput;
	}
	
	public function getParams()
	{
		return $this->_arrParams;
	}
	
	public function getUrl()
	{
		return $this->_url;
	}
	
	public function getVersion() 
	{
		return $this->_version;
	}
	
	public function getStart() 
	{
		return $this->_start;
	}
	
	public function getRows() 
	{
		return $this->_rows;
	}
	
	public function getIndent() 
	{
		return $this->_indent;
	}
	
	public function getFacet() 
	{		
		return $this->_facet;		
	}
	
	public function getFacetField() 
	{		
		return $this->_facetField;
	}
	
	public function getOutputType() 
	{		
		return $this->_outputType;		
	}
	
	public function getFq() 
	{
		return $this->_fq;
	}
	
	public function getFl() 
	{						
		return $this->_fl;				
	}

	public function setIsResultArray($isResultArray)
	{
		$this->isResultArray = $isResultArray;
	} 
	
	final public function _clean() 
	{
		$this->_q = null;	
		$this->_version = null;
		$this->_start = null;
		$this->_rows = null;
		$this->_indent = null;
		$this->_facet = null;
		$this->_facetPivot = null;
		$this->_group = null;
		$this->_groupField = null;
		$this->_facetField = null;
		$this->_fSiteFacetNumFacetTerms = null;
		$this->_facetMinCount = null;
		$this->_facetLimit = null;
		$this->_facetSort = null;
		$this->_outputType = null;
		$this->_fq = null;
		$this->_fl = null;
		$this->_url = null;		
		$this->_arrParams = null;		
	}
	
}
