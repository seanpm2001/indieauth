<!DOCTYPE html>
<html>
  <head>
    <title>IndieAuth</title>
    <meta charset='utf-8'>
    <script src='https://www.w3.org/Tools/respec/respec-w3c-common' async class='remove'></script>
    <script src='../assets/add-paragraph-ids.js' class='remove'></script>
    <script class='remove'>
      var respecConfig = {
          useExperimentalStyles: true,
          publishDate: "2017-12-04",
          specStatus: "NOTE",
          shortName:  "indieauth",
          edDraftURI: "https://indieauth.net/spec/",
          /* testSuiteURI: "https://indieauth.rocks/", */
          editors: [
                {   name:       "Aaron Parecki",
                    url:        "https://aaronparecki.com/",
                    w3cid:      "59996" }
          ],
          wg:           "Social Web Working Group",
          wgURI:        "https://www.w3.org/Social/WG",
          wgPublicList: "public-socialweb",
          wgPatentURI:  "https://www.w3.org/2004/01/pp-impl/72531/status",
          errata: "https://indieauth.net/errata",
          license: "w3c-software-doc",
          postProcess: [addParagraphIDs],
          otherLinks: [{
            key: 'Repository',
            data: [
              {
                value: 'Github',
                href: 'https://github.com/aaronpk/indieauth.net'
              },
              {
                value: 'Issues',
                href: 'https://github.com/aaronpk/indieauth.net/issues'
              },
              {
                value: 'Commits',
                href: 'https://github.com/aaronpk/indieauth.net/commits/master'
              }
            ]
          }],
          localBiblio:  {
            "microformats2-parsing": {
              title: "Microformats2 Parsing",
              href: "http://microformats.org/wiki/microformats2-parsing",
              authors: [
                "Tantek Çelik"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "h-entry": {
              title: "h-entry",
              href: "http://microformats.org/wiki/h-entry",
              authors: [
                "Tantek Çelik"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "h-app": {
              title: "h-app",
              href: "http://microformats.org/wiki/h-app",
              authors: [
                "Aaron Parecki"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "RelMeAuth": {
              title: "RelMeAuth",
              href: "http://microformats.org/wiki/RelMeAuth",
              authors: [
                "Tantek Çelik"
              ],
              status:   "Living Specification",
              publisher:  "microformats.org"
            },
            "URL": {
              title: "URL Standard",
              href: "https://url.spec.whatwg.org/",
              authors: ["Anne van Kesteren"],
              status: "Living Standard",
              publisher: "WHATWG"
            }
          }
      };
    </script>
    <link rel="pingback" href="https://indieauth.net/pingback.php">
    <link rel="webmention" href="https://indieauth.net/endpoint.php">
  </head>
  <body>
    <section id='abstract'>
      <p>
        IndieAuth is an identity layer on top of OAuth 2.0 [[!RFC6749]], primarily used to obtain
        an OAuth 2.0 Bearer Token [[!RFC6750]] for use by [[Micropub]] clients. End-Users
        and Clients are all represented by URLs. IndieAuth enables Clients to
        verify the identity of an End-User, as well as to obtain an access
        token that can be used to access resources under the control of the
        End-User.
      </p>

      <section id="authorsnote" class="informative">
        <h2>Author's Note</h2>
        <p>This specification was contributed to the W3C from the
          <a href="https://indieweb.org/">IndieWeb</a> community. More
          history and evolution of IndieAuth can be found on the
          <a href="https://indieweb.org/IndieAuth-spec">IndieWeb wiki</a>.</p>
      </section>
    </section>

    <section id='sotd'>
    </section>

    <section>
      <h2>Introduction</h2>

      <section class="informative">
        <h3>Background</h3>

        <p>The IndieAuth spec began as a way to obtain an OAuth 2.0 access token for use by Micropub clients. It can be used to both obtain an access token, as well as authenticate users signing to any application. It is built on top of the OAuth 2.0 framework, and while this document should provide enough guidance for implementers, referring to the core OAuth 2.0 spec can help answer any remaining questions. More information can be found <a href="https://indieweb.org/IndieAuth-spec">on the IndieWeb wiki</a>.</p>
      </section>

      <section>
        <h3>OAuth 2.0 Extension</h3>

        <p>IndieAuth builds upon the OAuth 2.0 [[RFC6749]] Framework as follows</p>

        <ul>
          <li>Specifies a format for user identifiers (a resolvable URL)</li>
          <li>Specifies a method of discovering the authorization and token endpoints given a profile URL</li>
          <li>Specifies a format for the Client ID (a resolvable URL)</li>
          <li>All clients are public clients</li>
          <li>Client registration at the authorization endpoint is not necessary, since client IDs are resolvable URLs</li>
          <li>Redirect URI registration happens by verifying data fetched at the Client ID URL</li>
          <li>Specifies a mechanism for returning user identifiers</li>
          <li>Specifies a mechanism for verifying authorization codes</li>
          <li>Specifies a mechanism for a token endpoint and authorization endpoint to communicate</li>
        </ul>
      </section>
    </section>

    <section>
      <h2>Conformance</h2>

      <p>The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT",
      "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this
      document are to be interpreted as described in [[!RFC2119]].</p>

      <section>
        <h3>Conformance Classes</h3>

        <p>An IndieAuth implementation can implement one or more of the roles of an IndieAuth server or client. This section describes the conformance criteria for each role.</p>

        <p>Listed below are known types of IndieAuth implementations.</p>

        <section>
          <h4>Authorization Endpoint</h4>
          <p>An IndieAuth Authorization Endpoint is responsible for obtaining authorization or authentication consent from the end user and generating and verifying authorization codes.</p>
        </section>

        <section>
          <h4>Token Endpoint</h4>
          <p>An IndieAuth Token Endpoint is responsible for generating and verifying OAuth 2.0 Bearer Tokens.</p>
        </section>

        <section>
          <h4>Micropub Client</h4>
          <p>A Micropub client will attempt to obtain an OAuth 2.0 Bearer Token given an IndieAuth profile URL, and will use the token when making Micropub requests.</p>
        </section>

        <section>
          <h4>IndieAuth Client</h4>
          <p>An IndieAuth client is a client that is attempting to authenticate a user given their profile URL, but does not need an OAuth 2.0 Bearer Token.</p>
        </section>

      </section>
    </section>

    <section>
      <h2>Identifiers</h2>

      <section>
        <h3>User Profile URL</h3>

        <p>Users are identified by a [[!URL]]. Profile URLs MUST have either an <code>https</code> or <code>http</code> scheme, MUST contain a path component (<code>/</code> is a valid path), MUST NOT contain single-dot or double-dot path segments, MAY contain a query string component, MUST NOT contain a fragment component, MUST NOT contain a username or password component, and MUST NOT contain a port. Additionally, hostnames MUST be domain names and MUST NOT be ipv4 or ipv6 addresses.</p>

        <p>Some examples of valid profile URLs are:</p>

        <ul>
          <li><code>https://example.com/</code></li>
          <li><code>https://example.com/username</code></li>
          <li><code>https://example.com/users?id=100</code></li>
        </ul>

        <p>Some examples of invalid profile URLs are:</p>
        <ul>
          <li><s><code>example.com</code></s> - missing scheme</li>
          <li><s><code>mailto:user@example.com</code></s> - invalid scheme</li>
          <li><s><code>https://example.com/foo/../bar</code></s> - contains a double-dot path segment</li>
          <li><s><code>https://example.com/#me</code></s> - contains a fragment</li>
          <li><s><code>https://user:pass@example.com/</code></s> - contains a username and password</li>
          <li><s><code>https://example.com:8443/</code></s> - contains a port</li>
          <li><s><code>https://172.28.92.51/</code></s> - host is an IP address</li>
        </ul>
      </section>

      <section>
        <h3>Client Identifier</h3>

        <p>Clients are identified by a [[!URL]]. Client identifier URLs MUST have either an <code>https</code> or <code>http</code> scheme, MUST contain a path component, MUST NOT contain single-dot or double-dot path segments, MAY contain a query string component, MUST NOT contain a fragment component, MUST NOT contain a username or password component, and MUST NOT contain a port. Additionally, hostnames MUST be domain names and MUST NOT be ipv4 or ipv6 addresses.</p>
      </section>

      <section>
        <h3>URL Canonicalization</h3>

        <p>Since IndieAuth uses https/http URLs which fall under what [[!URL]] calls "<a href="https://url.spec.whatwg.org/#special-scheme">Special URLs</a>", a string with no path component is not a valid [[!URL]]. As such, if a URL with no path component is ever encountered, it MUST be treated as if it had the path <code>/</code>. For example, if a user enters <code>https://example.com</code> as their profile URL, the client MUST transform it to <code>https://example.com/</code> when using it and comparing it.</p>

        <p>Since domain names are case insensitive, the hostname component of the URL MUST be compared case insensitively. Implementations SHOULD convert the hostname to lowercase when storing and using URLs.</p>
      </section>

    </section>

    <section>
      <h2>Discovery</h2>

      <p>This specification uses the link rel registry as defined by [[!HTML5]]
        for both HTML and HTTP link relations.</p>

      <section>
        <h3>Discovery by Clients</h3>

        <p>Clients need to discover a few pieces of information when a user signs in. For the <a href="#authentication">Authentication</a> workflow, the client needs to find the user's <code>authorization_endpoint</code>. For the <a href="#authorization">Authorization</a> workflow, especially when obtaining an access token for use at a [[Micropub]] endpoint, the client needs to find the user's <code>authorization_endpoint</code>, <code>token_endpoint</code> and <code>micropub</code> endpoint.</p>

        <p>Clients MUST start by making a GET request to [[!Fetch]] the user's profile URL to discover the necessary values. Clients MUST check for an HTTP Link header [[!RFC5988]] with the appropriate <code>rel</code> value. If the content type of the document is HTML, then the client MUST check for an HTML <code>&lt;link&gt;</code> element with the appropriate <code>rel</code> value.</p>

        <p>The endpoints discovered MAY be relative URLs, in which case the client MUST resolve it relative to the profile URL according to [[!URL]].</p>

        <p>Clients MAY initially make an HTTP HEAD request [[!RFC7231]] to check for the <code>Link</code> header before making a GET request.</p>
      </section>

      <section>
        <h3>Client Information Discovery</h3>

        <p>When an authorization server presents its <a href="https://www.oauth.com/oauth2-servers/authorization/the-authorization-interface/">authorization interface</a>, it will often want to display some additional information about the client beyond just the <code>client_id</code> URL, in order to better inform the user about the request being made. Additionally, the authorization server needs to know the list of redirect URLs that the client is allowed to redirect to.</p>

        <p>Since client identifiers are URLs, the authorization server SHOULD [[!Fetch]] the URL to find more information about the client.</p>

        <section>
          <h4>Application Information</h4>
  
          <p>Clients SHOULD have a web page at their <code>client_id</code> URL with basic information about the application, at least the application's name and icon. This page serves as a good landing page for human visitors, but can also serve as the place to include machine-readable information about the application. The HTML on the <code>client_id</code> URL SHOULD be marked up with [[!h-app]] [[Microformats]] to indicate the name and icon of the application. Authorization servers SHOULD support parsing the [[!h-app]] Microformat from the <code>client_id</code>, and if there is an [[!h-app]] with a <code>url</code> property matching the <code>client_id</code> URL, then it should use the name and icon and display them on the authorization prompt.</p>

          <pre class="example"><?= htmlspecialchars(
'<div class="h-app">
  <img src="/logo.png" class="u-logo">
  <a href="/" class="u-url p-name">Example App</a>
</div>') ?></pre>
        </section>

        <section>
          <h4>Redirect URL</h4>

          <p>If a client wishes to use a redirect URL that is on a different domain than their <code>client_id</code>, or if the redirect URL uses a custom scheme (such as when the client is a native application), then they will need to whitelist those redirect URLs so that authorization endpoints can be sure it is safe to redirect users there. The client SHOULD publish a <code>&lt;link&gt;</code> tag or a <code>Link</code> HTTP header with a <code>rel</code> attribute of <code>redirect_uri</code> at the <code>client_id</code> URL.</p>

          <pre class="example"><?= htmlspecialchars('GET / HTTP/1.1
Host: app.example.com

HTTP/1.1 200 Ok
Content-type: text/html; charset=utf-8
Link: <https://app.example.com/redirect>; rel="redirect_uri"

<!doctype html>
<html>
  <head>
    <link rel="redirect_uri" href="https://app.example.com/redirect">
  </head>
  ...
</html>') ?></pre>
        </section>

      </section>

    </section>

    <section>
      <h2>Authentication</h2>

      <p>This section describes how to perform authentication using the Authorization Code Flow.</p>

      <?php /*
---
title IndieAuth Authentication Flow Diagram

Browser->Client: User enters their profile URL
Client->User URL: Client fetches URL to discover\n**rel=authorization_endpoint**
Browser<--Client: Client builds authorization request and\nredirects to **authorization_endpoint**
Browser->Authorization Endpoint: User visits their authorization endpoint and sees the authorization request
Authorization Endpoint->Client: Authorization endpoint fetches client information (name, icon)
Browser<--Authorization Endpoint: User authenticates, and approves the request.\nAuthorization endpoint issues code, builds redirect back to client.
Browser->Client: User's browser is redirected to\nclient with an **authorization code**
Client->Authorization Endpoint: Client verifies authorization code by making a POST request\nto the authorization_endpoint
Client<--Authorization Endpoint: Authorization endpoint returns canonical user profile URL
Browser<--Client: Client initiates login session\nand the user is logged in
---

https://sequencediagram.org/index.html?initialData=C4S2BsFMAIEkDsAmJIEECuwAW0PcvKAMYCGoA9vNAGLjkDu0AIiCQOYBOJAtgFC8AhDgwDOkDgFoAfAGFwKQgC5oAVTEdoBYOJHR8IDQAdhAMxBRVAJQAyvOQuDS14q9eX2t0E5GBEskXRUbPXJoZBEicgA3cQAdeAAqBI5IcABeEkwscg4QAC8yEEoAfQJEQ3IQQiTBYXp1AB4JCQ8laFbgaAAjdHNEXUzsHPzCymgUgEd0AM6SJHiU5BSiYF1gUKTB7NyCinhSpAqq4BqhUXFpPG2RvegAUUPKtucNKJARMDX-A2gt4d2ilQykdCL8kNAxAE9P5fll-qMqJNpiJgLwrvDbg9yk9HLJ5FplOidgjNI9jl4fH4oUR8aCqiYctwSQAKeA8SAAGmgIEi8AAlLVzhwmhIiTdAfcyc91LD8IQeWQAly5ohfoZjNEofhxpApjMAHTxMUAsbAnHckQiZHQSKITndXrgfo6paQFbdEhEADWIRttOA+sF9QueIcyheAHJdF06jL3i6DG7tKr1vEaQ5oPQwDg5tBNnDibdbZAah1LgXxWMsSDgO5-dAYrkzFC-oWJcXugBPaBMr1VNi-aAABQA8gBlAAqOr1KPi62hMFblf2ZuOdn9IuNJOrOMJFZNQKlnRSwHQHHgulI8EoCvA0HQMo1ZgsQVsZ2DwuaHTrGaqYFY2i6HQbBVBCAQfJQ8Qqgu95xkB5BsGwkCqlUvBAA

Note: Change width/height to e.g. 
viewbox="0 0 906 716" style="width: 100%; height: auto;"
*/ ?>

      <?= file_get_contents('authentication-flow-diagram.svg') ?>

      <ul>
        <li>The End-User enters their profile URL in the login form of the client and clicks "Sign in"</li>
        <li>The client discovers the End-User's authorization endpoint by fetching the End-User's profile URL and looking for the <code>rel=authorization_endpoint</code> value</li>
        <li>The client builds the authorization request including its client identifier, local state, and a redirect URI, and redirects the browser to the authorization endpoint</li>
        <li>The authorization endpoint fetches the client information from the client identifier URL in order to have an application name and icon to display to the user</li>
        <li>The authorization endpoint verifies the End-User, e.g. by logging in, and establishes whether the End-User grants or denies the client's request</li>
        <li>The authorization endpoint generates an authorization code and redirects the browser back to the client, including an authorization code in the URL</li>
        <li>The client verifies the authorization code by making a POST request to the authorization endpoint. The authorization endpoint validates the authorization code, and responds with the End-User's canonical profile URL</li>
      </ul>

      <section>
        <h3>Discovery</h3>

        <p>After obtaining the End-User's profile URL, the client fetches the URL and looks for the <code>authorization_endpoint</code> rel value in the HTTP <code>Link</code> headers and HTML <code>&lt;link&gt;</code> tags.</p>

        <pre class="example"><?= htmlspecialchars('Link: <https://example.com/auth>; rel="authorization_endpoint"

<link rel="authorization_endpoint" href="https://example.com/auth">') ?></pre>
      </section>

      <section>
        <h3>Authorization Request</h3>

        <p>The client builds the authorization request URL with the following parameters:</p>
        <ul>
          <li><code>me</code> - The profile URL that the user entered</li>
          <li><code>client_id</code> - The client URL</li>
          <li><code>redirect_uri</code> - The redirect URL indicating where the user should be redirected to after approving the request</li>
          <li><code>state</code> - A parameter set by the client which will be included when the user is redirected back to the client. This is used to prevent CSRF attacks. The authorization server MUST return the unmodified state value back to the client.</li>
          <li><code>response_type=id</code> - Indicates to the authorization server that this is an authentication request</li>
        </ul>

        <pre class="example nohighlight"><?= htmlspecialchars(
'https://indieauth.com/auth?me=https://user.example.net/&
                           redirect_uri=https://app.example.com/redirect&
                           client_id=https://app.example.com/&
                           state=1234567890&
                           response_type=id') ?></pre>

        <p>The authorization endpoint SHOULD fetch the <code>client_id</code> URL to retrieve application information and the client's registered redirect URLs, see <a href="#client-information-discovery">Client Information Discovery</a> for more information.</p>

        <p>If the <code>redirect_uri</code> in the request has a different domain than the <code>client_id</code>, then the authorization endpoint SHOULD verify that the requested <code>redirect_uri</code> matches one of the <a href="#redirect-url">redirect URLs</a> published by the client, and SHOULD block the request from proceeding if not.</p>

        <p>It is up to the authorization endpoint how to authenticate the user. This step is out of scope of OAuth 2.0, and is highly dependent on the particular implementation. Some authorization servers use typical username/password authentication, and others use alternative forms of authentication such as [[RelMeAuth]].</p>

        <p>Once the user is authenticated, the authorization endpoint presents the authentication prompt to the user. The prompt MUST indicate which application the user is signing in to, and SHOULD provide as much detail as possible about the request.</p>
      </section>

      <section>
        <h3>Authorization Response</h3>

        <p>If the user approves the request, the authorization endpoint generates an authorization code and builds the redirect back to the client.</p>

        <p>The redirect is built by starting with the <code>redirect_uri</code> in the request, and adding the following parameters to the query component of the redirect URL:</p>

        <ul>
          <li><code>code</code> - The authorization code generated by the authorization endpoint. The code MUST expire shortly after it is issued to mitigate the risk of leaks. A maximum lifetime of 10 minutes is recommended. See <a href="https://tools.ietf.org/html/rfc6749#section-4.1.2">OAuth 2.0 Section 4.1.2</a> for additional requirements on the authorization code.</li>
          <li><code>state</code> - The state parameter MUST be set to the exact value that the client set in the request.</li>
        </ul>

        <pre class="example nohighlight"><?= htmlspecialchars(
'HTTP/1.1 302 Found
Location: https://app.example.com/redirect?code=xxxxxxxx
                                           state=1234567890') ?></pre>
      </section>

      <section>
        <h3>Authorization Code Verification</h3>

        <p>The client MUST verify that the state parameter in the request is valid and matches the state parameter that it initially created, in order to prevent CSRF attacks. The state value can also store session information to enable development of clients that cannot store data themselves.</p>

        <h4>Request</h4>

        <p>After the state parameter is validated, the client makes a POST request to the authorization endpoint to verify the authorization code and return the final user profile URL. The POST request contains the following parameters:</p>

        <ul>
          <li><code>code</code> - The authorization code received from the authorization endpoint in the redirect</li>
          <li><code>client_id</code> - The client's URL, which MUST match the client_id used in the authorization request.</li>
          <li><code>redirect_uri</code> - The client's redirect URL, which MUST match the initial authorization request.</li>
        </ul>

        <pre class="example nohighlight"><?= htmlspecialchars(
'POST https://auth.example.org/auth
Content-type: application/x-www-form-urlencoded

code=xxxxxxxx
&client_id=https://app.example.com/
&redirect_uri=https://app.example.com/redirect
') ?></pre>

        <h4>Response</h4>

        <p>The authorization endpoint verifies that the authorization code is valid, and that it was issued for the matching <code>client_id</code> and <code>redirect_uri</code>. If the request is valid, then the endpoint responds with a JSON [[!RFC7159]] object containing one property, <code>me</code>, with the canonical user profile URL for the user who signed in.</p>

        <pre class="example nohighlight"><?= htmlspecialchars(
'HTTP/1.1 200 OK
Content-Type: application/json

{
  "me": "https://user.example.org/"
}') ?></pre>

        <p>The resulting profile URL MAY be different from what the user initially entered, but MUST be on a matching domain. This gives the authorization endpoint an opportunity to canonicalize the user's URL, such as correcting <code>http</code> to <code>https</code>, or adding a path if required.</p>

      </section>
    </section>


    <section>
      <h2>Authorization</h2>

      <p>This section describes how to obtain an access token using the Authorization Code Flow.</p>

<?php /*
---
title IndieAuth Authentication Flow Diagram

Browser->Client: User enters their profile URL
Client->User URL: Client fetches URL to discover\n**rel=authorization_endpoint**\nand **rel=token_endpoint**
Browser<--Client: Client builds authorization request and\nredirects to **authorization_endpoint**
Browser->Authorization Endpoint: User visits their authorization endpoint and sees the authorization request
Authorization Endpoint->Client: Authorization endpoint fetches client information (name, icon)
Browser<--Authorization Endpoint: User authenticates, and approves the request.\nAuthorization endpoint issues code, builds redirect back to client.
Browser->Client: User's browser is redirected to\nclient with an **authorization code**
Client->Token Endpoint: Client exchanges authorization code for an \naccess token by making a POST request\nto the token_endpoint
Client<--Token Endpoint: Token endpoint verifies code and returns\ncanonical user profile URL with an access token
Browser<--Client: Client initiates login session\nand the user is logged in
---

https://sequencediagram.org/index.html?initialData=C4S2BsFMAIEkDsAmJIEECuwAW0PcvKAMYCGoA9vNAGLjkDu0AIiCQOYBOJAtgFC8AhDgwDOkDgFoAfAGFwKQgC5oAVTEdoBYOJHR8IDQAdhAMxBRVAJQAyvOQuDS14q9eX2t0E5GBEskXRUbPXJoZBEicgA3cQAdeAAqBI5IcABeEkwscg4QAC8yEEoAfQJEQ3IQQiT4kiRoJJT04HIAawJSpAqq4CTBYXp1AB4JCQ8laHHgaAAjdHNEXUzsHPzCymgUgEd0AOm6xHiU5BSiYF0WhoTl7NyCinhO8srqhP7RcWk8W7WH6ABRLovYDKZwaKIgERgC7+AzQG6re5FKhlbqEeH1MQBPT+eFZRHrKjbXYiYC8b4Ev6A549aRTZQUu6EzRAnpeHx+bFEeSeKomHLcZkACngPEgABpoCBIvAAJTvQbiEYSRm-ZEA1kTMF4-CEaVkAKSg7wwzGaLY-CbSA7PYAOniqqRG1RwKlIhEJOgkUQEtm83AiytJ0gZ1mJCIrRCXp5hFtCvUdJjINU6gA5LoZgN1G6gwYQ9pECF4tyHNB6GAcHUrgimX9vZA+lNpAAVNoEDU0iZTTQADz8dTY2Jrao29a8OQx0FqRCIAQubaoMwAntBBa0qmx4dAAAoAeQAys2rTbSfFLpaWu1Hi6enYk8rW1eO2jk4-2zf0TFcmYueQfRjCxSYB0A4eARGLOpKH1cBoHQbMzTMCwgmsMsK0ncNZ3dEIr3jJVRnpSYkyleAwFYbRdDoNgqmgMR3WRWp6ktOCXEhaBKMHQsql4IA

Note: Change width/height to e.g. 
viewbox="0 0 906 716" style="width: 100%; height: auto;"
*/ ?>

      <?= file_get_contents('authorization-flow-diagram.svg') ?>

      <ul>
        <li>The End-User enters their profile URL in the login form of the client and clicks "Sign in"</li>
        <li>The client discovers the End-User's authorization endpoint and token endpoint by fetching the profile URL and looking for the <code>rel=authorization_endpoint</code> and <code>rel=token_endpoint</code> values</li>
        <li>The client redirects the browser to the authorization endpoint, including its client identifier, requested scope, local state, and a redirect URL</li>
        <li>The authorization endpoint verifies the End-User, e.g. by logging in, and establishes whether the End-User grants or denies the client's request</li>
        <li>The authorization endpoint redirects the browser to the client's redirect URL, including an authorization code</li>
        <li>The client exchanges the authorization code for an access token by making a POST request to the token endpoint. The token endpoint validates the authorization code, and responds with the End-User's canonical profile URL and an access token</li>
      </ul>

      <section>
        <h3>Discovery</h3>

        <p>After obtaining the End-User's profile URL, the client fetches the URL and looks for the <code>authorization_endpoint</code> and <code>token_endpoint</code> rel values in the HTTP <code>Link</code> headers and HTML <code>&lt;link&gt;</code> tags.</p>

        <pre class="example"><?= htmlspecialchars(
'Link: <https://example.com/auth>; rel="authorization_endpoint"
Link: <https://example.com/token>; rel="token_endpoint"

<link rel="authorization_endpoint" href="https://example.com/auth">
<link rel="token_endpoint" href="https://example.com/token">') ?></pre>
      </section>

      <section>
        <h3>Authorization Endpoint</h3>

        <section>
          <h4>Authorization Request</h4>

        </section>

        <section>
          <h4>Authorization Response</h4>

        </section>
      </section>

      <section>
        <h3>Token Endpoint</h3>

        <section>
          <h4>Token Request</h4>

        </section>

        <section>
          <h4>Authorization Code Verification</h4>

        </section>

        <section>
          <h4>Access Token Response</h4>

        </section>
      </section>

    </section>


    <section>
      <h2>Security Considerations</h2>


    </section>

    <section>
      <h2>IANA Considerations</h2>
      
      <p>The link relation type below has been registered by IANA per Section 6.2.1 of [[!RFC5988]]:</p>
      
      <dl>
        <dt>Relation Name:</dt>
        <dd>authorization_endpoint</dd>
        
        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 authorization endpoint given an IndieAuth profile URL.</dd>
        
        <dt>Reference:</dt>
        <dd><a href="http://www.w3.org/TR/indieauth/">W3C IndieAuth
        Specification (http://www.w3.org/TR/indieauth/)</a></dd>
      </dl>

      <dl>
        <dt>Relation Name:</dt>
        <dd>token_endpoint</dd>
        
        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 token endpoint given an IndieAuth profile URL.</dd>
        
        <dt>Reference:</dt>
        <dd><a href="http://www.w3.org/TR/indieauth/">W3C IndieAuth
        Specification (http://www.w3.org/TR/indieauth/)</a></dd>
      </dl>

      <dl>
        <dt>Relation Name:</dt>
        <dd>redirect_uri</dd>
        
        <dt>Description:</dt>
        <dd>Used for discovery of the OAuth 2.0 redirect URI given an IndieAuth client ID.</dd>
        
        <dt>Reference:</dt>
        <dd><a href="http://www.w3.org/TR/indieauth/">W3C IndieAuth
        Specification (http://www.w3.org/TR/indieauth/)</a></dd>
      </dl>
    </section>

    <!--
    <section class="appendix informative">
      <h2>Extensions</h2>

      <p>The following Webmention Extension Specifications have 2+ interoperable implementations live on the web and are thus listed here:</p>

    </section>
  -->

    <section class="appendix informative">
      <h2>Resources</h2>

      <p>
        <ul>
          <!-- <li><a href="https://indieauth.rocks">Test Suite and Debug Tool</a></li> -->
          <li><a href="https://indieweb.org/Category:IndieAuth">More IndieAuth resources</a></li>
          <li><a href="https://indieweb.org/obtaining-an-access-token">Implementation guide for obtaining an access token using IndieAuth</a></li>
          <li><a href="https://indieweb.org/indieauth-for-login">Implementation guide for authenticating users without obtaining an access token</a></li>
        </ul>
      </p>

      <section class="appendix informative">
        <h3>Articles</h3>

        <p>You can find a list of <a href="https://indieweb.org/IndieAuth#Articles">articles about IndieAuth</a> on the IndieWeb wiki.</p>
      </section>

      <section class="appendix informative">
        <h3>Implementations</h3>

        <p>You can find a list of <a href="https://indieauth.net/implementations">IndieAuth implementations</a> on indieauth.net</p>
      </section>

    </section>

    <section class="appendix">
      <h2>Acknowledgements</h2>

      <p>The editor wishes to thank the <a href="https://indieweb.org/">IndieWeb</a>
        community and other implementers for their support, encouragement and enthusiasm,
        including but not limited to: Amy Guy, Barnaby Walters, Benjamin Roberts, Bret Comnes, Christian Weiske, François Kooman, Jeena Paradies, Martijn van der Ven, Sebastiaan Andeweg, Sven Knebel, and Tantek Çelik.</p>
    </section>

    <!--
    <section class="appendix informative">
      <h2>Change Log</h2>

      <section>
        <h3>Changes from <a href="">00 November NOTE</a> to this version</h3>

        <ul>

        </ul>
      </section>
    </section>
    -->

    <script>
    // After text is selected (mouseup), find the closest element that has an ID
    // attribute, and update the browser location bar to include that as the fragment
    document.body.onmouseup = function(){
      var selection;
      if(selection=window.getSelection()) {
        var range = selection.getRangeAt(0);
        var el = range.startContainer;
        while((el=el.parentElement) && !el.attributes["id"]);
        var hash = el.attributes["id"].value;
        if(history.replaceState) {
          history.replaceState(null, null, "#"+hash);
        }
      }
    };
    </script>
  </body>
</html>