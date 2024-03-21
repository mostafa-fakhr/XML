<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:template match="/">
    <html>
      <head>
        <title>Employee Information</title>
        <style>
          table {
            width: 100%;
            border-collapse: collapse;
          }
          th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
          }
          th {
            background-color: #f2f2f2;
          }
        </style>
      </head>
      <body>
        <h1>Employee Information</h1>
        <table>
          <tr>
            <th>Name</th>
            <th>Home Phone</th>
            <th>Work Phone</th>
            <th>Mobile Phone</th>
            <th>Street</th>
            <th>Building</th>
            <th>Region</th>
            <th>City</th>
            <th>Country</th>
            <th>Email</th>
          </tr>
          <xsl:apply-templates select="//employee"/>
        </table>
      </body>
    </html>
  </xsl:template>
  
  <xsl:template match="employee">
    <tr>
      <td><xsl:value-of select="name"/></td>
      <td><xsl:value-of select="phones/phone[@type='Home']"/></td>
      <td><xsl:value-of select="phones/phone[@type='Work']"/></td>
      <td><xsl:value-of select="phones/phone[@type='moblie']"/></td>
      <td><xsl:value-of select="addresses/address/street"/></td>
      <td><xsl:value-of select="addresses/address/building"/></td>
      <td><xsl:value-of select="addresses/address/region"/></td>
      <td><xsl:value-of select="addresses/address/city"/></td>
      <td><xsl:value-of select="addresses/address/country"/></td>
      <td><xsl:value-of select="mail"/></td>
    </tr>
  </xsl:template>
  
</xsl:stylesheet>
