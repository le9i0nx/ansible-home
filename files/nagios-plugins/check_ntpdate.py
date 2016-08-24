#!/usr/bin/env python3

"""
    by Aleksey Gavrilov <le9i0nx@gmail.com>

"""

from optparse import OptionParser
import sys
import subprocess

check_ver = '0.1'

# Parse commandline options:
parser = OptionParser(usage="%prog [ -w <warning threshold> ] [ -c <critical threshold> ] [ -h ]",version="%prog " + check_ver)
parser.add_option("-w", "--warning",
    action="store",
    type="float",
    default=0.1,
    dest="warn_threshold",
    help="Warning threshold in second")
parser.add_option("-c", "--critical",
    action="store",
    type="float",
    default=0.2,
    dest="crit_threshold",
    help="Critical threshold in second")
parser.add_option("-p", "--pool",
    action="store",
    type="string",
    default="pool.ntp.org",
    dest="pool",
    help="ntp server default=pool.ntp.org")
(options, args) = parser.parse_args()

def ntpdate():
    outs = subprocess.check_output( [ '/usr/sbin/ntpdate', '-q', options.pool ] )
    for x in outs.decode("utf-8").splitlines():
        if 'ntpdate' in x:
            return float(x.split(" ")[-2])

def go():
    if not options.crit_threshold:
        print("UNKNOWN: Missing critical threshold value.")
        sys.exit(3)
    if not options.warn_threshold:
        print("UNKNOWN: Missing warning threshold value.")
        sys.exit(3)
    if options.crit_threshold <= options.warn_threshold:
        print("UNKNOWN: Critical percentage can't be equal to or bigger than warning second.")
        sys.exit(3)
    time = ntpdate()
    rezult_str = " Offset {0} secs|offset={0}s;{1};{2};".format(time,options.warn_threshold,options.crit_threshold)
    if abs(time) >= float(options.crit_threshold):
        print("NTP CRITICAL:"+ rezult_str)
        sys.exit(2)
    elif abs(time) >= float(options.warn_threshold):
        print("NTP WARNING:"+ rezult_str)
        sys.exit(1)
    else:
        print("NTP OK:"+ rezult_str)
        sys.exit(0)

if __name__ == '__main__':
    go()

