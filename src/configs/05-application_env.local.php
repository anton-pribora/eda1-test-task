<?php

Config()->set('APPLICATION_ENV', 'development');

putenv('APPLICATION_ENV=' . Config()->get('APPLICATION_ENV'));
