<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Order comments</title>
                <link href="../styles/master.css" rel="stylesheet" />
            </head>
            <body>
                <xsl:apply-templates />
            </body>
        </html>
    </xsl:template>

    <xsl:template match="/error">
        <div class="comment comment-empty">
            <div><xsl:value-of select="node()" /></div>
            <form method="GET">
            <label for="enter-id">Enter order ID:</label>
            <input name="id" type="number" min="0" id="enter-id"></input>
            <button>View</button>
            </form>
        </div>
    </xsl:template>

    <xsl:template match="/order">
        <header class="order-details">
            <h3>Order #<xsl:value-of select="id" /></h3>
            <div class="order-details__wrapper">
                <div class="order-details__people">
                    <xsl:apply-templates select="/order/worker"/>
                    <xsl:apply-templates select="/order/client"/>
                </div>
                <div class="order-details__misc">
                    <div class="order-details__device">
                        <xsl:value-of select="deviceInfo" />
                    </div>
                    <div class="order-details__type">
                        <xsl:value-of select="orderType" />
                    </div>
                    <div class="order-details__price">
                        <xsl:value-of select="price"/>
                        <xsl:text>&#xA0;₽</xsl:text>
                    </div>
                </div>
            </div>
        </header>
        <main class="order-comments">
            <xsl:apply-templates select="/order/comments" />
        </main>
    </xsl:template>

    <xsl:template match="/order/worker" >
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

    <xsl:template match="/order/client" >
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

    <xsl:template match="/order/comments">
        <xsl:choose>
            <xsl:when test="not(node())">
                <div class="comment comment-empty">No comments here yet.</div>
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates select="comment"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="/order/comments/comment">
        <div class="comment">
            <span class="comment__text"><xsl:value-of select="text"/></span>
            <span class="comment__ts"><xsl:value-of select="createdAt"/></span>
        </div>
    </xsl:template>
</xsl:stylesheet>