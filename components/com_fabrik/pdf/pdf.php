<?php
/**
 * PDF Document class
 *
 * @package     Joomla
 * @subpackage  Fabrik.Documents
 * @copyright   Copyright (C) 2005-2020  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

use Fabrik\Helpers\Pdf;
use Fabrik\Helpers\Text;

file_exists(JPATH_LIBRARIES . '/joomla/document/html/html.php') && require_once JPATH_LIBRARIES . '/joomla/document/html/html.php';


/**
 * DocumentPDF class, provides an easy interface to parse and display a pdf document
 *
 * @package     Joomla.Framework
 * @subpackage  Document
 * @since       1.5
 */
class JDocumentpdf extends JDocumentHTML
{
    private $engine = null;

    private $name = 'joomla';

    /**
     * Class constructor
     *
     * @param   array  $options  Associative array of options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);

        $config = JComponentHelper::getParams('com_fabrik');
        if ($config->get('pdf_debug', false))
        {
            $this->setMimeEncoding('text/html');
            $this->_type = 'pdf';
        }
        else
        {
            // Set mime type
            $this->_mime = 'application/pdf';

            // Set document type
            $this->_type = 'pdf';
        }
        if (!$this->iniDomPdf())
        {
            throw new RuntimeException(Text::_('COM_FABRIK_NOTICE_DOMPDF_NOT_FOUND'));
        }
    }

    /**
     * Set up DomPDF engine
     *
     * @return  bool
     */
    protected function iniDomPdf()
    {
        $this->engine = Pdf::iniDomPdf(true);

        return $this->engine;
    }

    /**
     * Set the paper size and orientation
     * Note if too small for content then the pdf renderer will bomb out in an infinite loop
     * Legal seems to be more lenient than a4 for example
     * If doing landscape set large paper size
     *
     * @param   string  $size         Paper size E.g A4,legal
     * @param   string  $orientation  Paper orientation landscape|portrait
     *
     * @since 3.0.7
     *
     * @return  void
     */
    public function setPaper($size = 'A4', $orientation = 'landscape')
    {
        $size = strtoupper($size);
        $this->engine->set_paper($size, $orientation);
    }

    /**
     * Sets the document name
     *
     * @param   string  $name  Document name
     *
     * @return  void
     */
    public function setName($name = 'joomla')
    {
        $this->name = $name;
    }

    /**
     * Returns the document name
     *
     * @return	string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Render the document.
     *
     * @param   boolean  $cache   If true, cache the output
     * @param   array    $params  Associative array of attributes
     *
     * @return	string
     */
    public function render($cache = false, $params = array())
    {
        // mb_encoding foo when content-type had been set to text/html; uft-8;
        $this->_metaTags['http-equiv'] = array();
        $this->_metaTags['http-equiv']['content-type'] = 'text/html';

        // Testing using futural font.
        // $this->addStyleDeclaration('body: { font-family: futural !important; }');
        $pdf = $this->engine;
        $data = parent::render();
        Pdf::fullPaths($data);

        /**
         * I think we need this to handle some HTML entities when rendering otherlanguages (like Polish),
         * but haven't tested it much
         */
        $data = mb_convert_encoding($data,'HTML-ENTITIES','UTF-8');

        $pdf->load_html($data);
        $config = JComponentHelper::getParams('com_fabrik');

        if ($config->get('pdf_debug', false))
        {
            return $pdf->output_html();
        }
        else
        {
            $pdf->render();
            $pdf->stream($this->getName() . '.pdf');
        }

        return '';
    }

    /**
     * Get the contents of a document include
     *
     * @param   string  $type     The type of renderer
     * @param   string  $name     The name of the element to render
     * @param   array   $attribs  Associative array of remaining attributes.
     *
     * @return  The output of the renderer
     */
    public function getBuffer($type = null, $name = null, $attribs = array())
    {
        if ($type == 'head' || $type == 'component')
        {
            return parent::getBuffer($type, $name, $attribs);
        }
        else
        {
            return '';
        }
    }
}
