<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>All orders</title>
                <link href="../styles/master.css" rel="stylesheet" />
            </head>
            <body>
                <main class="all-orders">
                    <table class="orderstbl">
                        <thead>
                            <th>#</th>
                            <th>Worker</th>
                            <th>Client</th>
                            <th>Device</th>
                            <th>Type</th>
                            <th>Cost</th>
                        </thead>
                        <tbody>
                            <xsl:apply-templates select="/orders/order" />
                        </tbody>
                    </table>
                </main>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="/orders/order">
        <tr>
            <td><xsl:value-of select="id"/></td>
            <td><xsl:apply-templates select="worker"/></td>
            <td><xsl:apply-templates select="client"/></td>
            <td><xsl:value-of select="deviceInfo"/></td>
            <td><xsl:value-of select="orderType"/></td>
            <td><xsl:text>$ </xsl:text><xsl:value-of select="price"/></td>
        </tr>
    </xsl:template>

    <xsl:template match="/orders/order/worker" >
        <div class="order-details__worker">
            <span>👷
                <strong><xsl:value-of select="lastName"/></strong>
                <xsl:text>,&#xA0;</xsl:text>
                <xsl:value-of select="firstName"/>
                <xsl:text>&#xA0;</xsl:text>
                <xsl:value-of select="middleName"/>
            </span>
        </div>
    </xsl:template>

    <xsl:template match="/orders/order/client" >
        <div class="order-details__client">
            <span>🤑
                <strong><xsl:value-of select="lastName"/></strong>
                <xsl:text>,&#xA0;</xsl:text>
                <xsl:value-of select="firstName"/>
                <xsl:text>&#xA0;</xsl:text>
                <xsl:value-of select="middleName"/>
            </span>
            <div class="client__contacts">
                <span class="client__phone">📞
                <xsl:choose>
                    <xsl:when test="phone">
                        <a>
                            <xsl:attribute name="href">
                                <xsl:value-of select="concat('tel:', phone)"/>
                            </xsl:attribute>
                            <xsl:value-of select="phone"/>
                        </a>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text>Not&#xA0;stated</xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
                </span>
                <span class="client__email">📧
                <xsl:choose>
                    <xsl:when test="email">
                        <a>
                            <xsl:attribute name="href">
                                <xsl:value-of select="concat('mailto:', email)"/>
                            </xsl:attribute>
                            <xsl:value-of select="email"/>
                        </a>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text>Not&#xA0;stated</xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
                </span>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>