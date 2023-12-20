/*

function -> cache -> se não tiver nada -> buscar servidor -> cached -> sem mais request no servidor

reduce -> latency, bandwidth, load on the server

locations ->  
  browser -> reutilizar -> privado (header)
  proxy server ->
  Reverse Proxy Server -> 

terminology
  client -> 
  origin server -> provide the content
  stale content -> content expired
  fresh content -> content usable

ACTIONS
  cache validation -> check if content expired -> update if expired
  cache invalidation -> remove content

How does the content get cached?
  HTTP Headers -> response
    Caching headers
      Expires, pragma - Less used, HTTP 1.1
      expires -> expire date -> more than 1 year X -> invalid value, wrong date format = stale response
      pragma -> prevent caching -> cache-control prefered

      Cache-Control - prefered, HTTP 1.1
        Private -> private to the user -> cached client or brwoser
        Public -> multiples users -> cached client and proxies
        no-store -> don't cache -> must call the server
        no-cache -> can be cached -> require server validation
        max-age=seconds -> time client content can be cached  
        s-max-age=seconds -> time proxy content can be cached

        Server no reachable
        must-revalidate -> stale content -> must validate (client)
        proxy-revalidate -> stale content at proxy -> must validate (proxy)

    Validators -> client -> usable content
      Server
        etag - HTTP 1.1 -> uid -> used to verify if the content changed
        etag: "strong"
        etag: w/"weak"
        last-modified -> content last modified

      Client
        if-none-match -> has etag value ->  
        if-modified-since ->  stale content -> request server -> 304 or new response

    Caching strategy
      light caching (HTML) -> cache-control : private, no-cache
      Agressive caching (CSS, JavaScript, images ) -> cache-control : public, max-age="31556926"

    Debugging the caching headers?
      dev tools -> headers
      curl -I http://localhost:5173.com
*/