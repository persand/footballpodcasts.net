from __future__ import with_statement

import os.path

from fabric.api import *
from fabric.contrib.project import *

env.user = 'root'
env.hosts = ['80.169.183.93']
env.remote_dir = '/mnt/persist/www/footballpodcasts.net'

def deploy(where=None):
  rsync_project(
    env.remote_dir,
    '/Users/per/Sites/footballpodcasts.net/jekyll/_site/',
    ['.git', '.git*', 'fabfile.py*', 'composer.*', '.DS_Store', '.htaccess'],
    True
  )
