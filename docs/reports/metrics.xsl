<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="utf-8" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" 
     doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>

  <xsl:template match="/">
    <xsl:variable name="metrics" select="/metrics"/>
    <html>
      <head>
        <title>Metrics Report</title>
        <link href="report.css" rel="stylesheet" type="text/css"/>
        <script src="report.js" type="text/javascript"></script>
      </head>
      <body>
        <!--<h1>Metrics Report</h1>-->
        <table cellspacing="0" cellpadding="0">
          <tbody>
            <xsl:for-each select="$metrics/file">
              <xsl:variable name="position" select="position()"/>
              <tr class="tr_file">
                <xsl:attribute name="id">metrics_link_<xsl:value-of select="$position"/>_1</xsl:attribute>
                <td colspan="9">
                  <a href="#">
                    <xsl:attribute name="onclick">toggleBody('metrics', '<xsl:value-of select="$position"/>'); return false;</xsl:attribute>
                    <span>+ </span>
                    <xsl:value-of select="@name"/>
                  </a>
                </td>
              </tr>
              <tr class="tr_info">
                <xsl:attribute name="id">metrics_info_<xsl:value-of select="$position"/>_1</xsl:attribute>
                <td colspan="9">
                  <strong class="head">File:</strong>
                  &#160;
                  <strong>Loc:</strong>&#160;<xsl:value-of select="@loc"/> |
                  <strong>Cloc:</strong>&#160;<xsl:value-of select="@cloc"/> |
                  <strong>NCloc:</strong>&#160;<xsl:value-of select="@ncloc"/> |
                  <strong>LocExecutable:</strong>&#160;<xsl:value-of select="@locExecutable"/> |
                  <strong>Coverage:</strong>&#160;<xsl:value-of select="@coverage"/>
                  <br/>
                  <strong class="head">Class:</strong>
                  &#160;
                  <strong>Loc:</strong>&#160;<xsl:value-of select="class/@loc"/> |
                  <strong>LocExecutable:</strong>&#160;<xsl:value-of select="class/@locExecutable"/> |
                  <strong>LocExecuted:</strong>&#160;<xsl:value-of select="class/@locExecuted"/> |
                  <strong>Coverage:</strong>&#160;<xsl:value-of select="class/@coverage"/>
                </td>
              </tr>
              <tr class="tr_head">
                <xsl:attribute name="id">metrics_head_<xsl:value-of select="$position"/>_1</xsl:attribute>
                <th>Method</th>
                <th>Loc</th>
                <th>LocExecutable</th>
                <th>LocExecuted</th>
                <th>Coverage</th>
                <th>CCN</th>
                <th>Crap</th>
                <th>NPath</th>
                <th>Parameters</th>
              </tr>
              <xsl:for-each select="class/method">
                <xsl:sort select="@name" order="ascending"/>
                <tr class="tr_body">
                  <xsl:attribute name="id">metrics_body_<xsl:value-of select="$position"/>_<xsl:value-of select="position()"/></xsl:attribute>
                  <td>
                    <xsl:value-of select="@name"/>
                  </td>
                  <td>
                    <xsl:value-of select="@loc"/>
                  </td>
                  <td>
                    <xsl:value-of select="@locExecutable"/>
                  </td>
                  <td>
                    <xsl:value-of select="@locExecuted"/>
                  </td>
                  <td>
                    <xsl:attribute name="class">
                      <xsl:choose>
                        <xsl:when test="number(@coverage) &lt; 30">error</xsl:when>
                        <xsl:when test="number(@coverage) &lt; 70">warning</xsl:when>
                        <xsl:otherwise>normal</xsl:otherwise>
                      </xsl:choose>
                    </xsl:attribute>
                    <xsl:value-of select="@coverage"/>
                  </td>
                  <td>
                    <xsl:value-of select="@ccn"/>
                  </td>
                  <td>
                    <xsl:attribute name="class">
                      <xsl:choose>
                        <xsl:when test="number(@crap) &gt; 30">error</xsl:when>
                        <xsl:otherwise>normal</xsl:otherwise>
                      </xsl:choose>
                    </xsl:attribute>
                    <xsl:value-of select="@crap"/>
                  </td>
                  <td>
                    <xsl:attribute name="class">
                      <xsl:choose>
                        <xsl:when test="number(@npath) &gt; 200">error</xsl:when>
                        <xsl:otherwise>normal</xsl:otherwise>
                      </xsl:choose>
                    </xsl:attribute>
                    <xsl:value-of select="@npath"/>
                  </td>
                  <td>
                    <xsl:attribute name="class">
                      <xsl:choose>
                        <xsl:when test="number(@parameters) &gt; 5">warning</xsl:when>
                        <xsl:otherwise>normal</xsl:otherwise>
                      </xsl:choose>
                    </xsl:attribute>
                    <xsl:value-of select="@parameters"/>
                  </td>
                </tr>
              </xsl:for-each>
            </xsl:for-each>
          </tbody>
        </table>
        <script type="text/javascript">init('metrics');</script>
      </body>
    </html>
  </xsl:template>
  
</xsl:stylesheet>