<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="utf-8" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" 
     doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>

  <xsl:template match="/">
    <xsl:variable name="pmd" select="/pmd"/>
    <html>
      <head>
        <title>PMD Report</title>
        <link href="report.css" rel="stylesheet" type="text/css"/>
        <script src="report.js" type="text/javascript"></script>
      </head>
      <body>
        <!--<h1>PMD Report</h1>-->
        <table cellspacing="0" cellpadding="0">
          <tbody>
            <xsl:for-each select="$pmd/file">
              <xsl:variable name="position" select="position()"/>
              <tr class="tr_file">
                <xsl:attribute name="id">pmd_link_<xsl:value-of select="$position"/>_1</xsl:attribute>
                <td colspan="4">
                  <a href="#">
                    <xsl:attribute name="onclick">toggleBody('pmd', '<xsl:value-of select="$position"/>'); return false;</xsl:attribute>
                    <span>+ </span>
                    <xsl:value-of select="@name"/>
                  </a>
                </td>
              </tr>
              <tr class="tr_head">
                <xsl:attribute name="id">pmd_head_<xsl:value-of select="$position"/>_1</xsl:attribute>
                <th>Rule</th>
                <th>Class</th>
                <th>Method</th>
                <th>Message</th>
              </tr>
              <xsl:for-each select="violation">
                <xsl:sort select="@method" order="ascending"/>
                <tr class="tr_body">
                  <xsl:attribute name="id">pmd_body_<xsl:value-of select="$position"/>_<xsl:value-of select="position()"/></xsl:attribute>
                  <td>
                    <xsl:value-of select="@rule"/>
                  </td>
                  <td>
                    <xsl:value-of select="@class"/>
                  </td>
                  <td>
                    <xsl:value-of select="@method"/>
                  </td>
                  <td>
                    <xsl:value-of select="text()"/>
                  </td>
                </tr>
              </xsl:for-each>
            </xsl:for-each>
          </tbody>
        </table>
        <script type="text/javascript">init('pmd');</script>
      </body>
    </html>
  </xsl:template>
  
</xsl:stylesheet>