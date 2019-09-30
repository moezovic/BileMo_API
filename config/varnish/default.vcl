# /etc/varnish/default.vcl

include "path-to-config/varnish/fos_purge.vcl";
include "path-to-config/varnish/fos_ban.vcl";

acl invalidators {
    "localhost";
    # Add any other IP addresses that your application runs on and that you
    # want to allow invalidation requests from. For instance:
    # "192.168.1.0"/24;
}

sub vcl_recv {
    call fos_purge_recv;
    call fos_ban_recv;
}

sub vcl_backend_response {
    call fos_ban_backend_response;
}

sub vcl_deliver {
    call fos_ban_deliver;
}