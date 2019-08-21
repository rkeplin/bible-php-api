#!/bin/bash
openssl aes-256-cbc -K $encrypted_42099b4af021_key -iv $encrypted_42099b4af021_iv -in $(pwd)/.deploy/travis_id_rsa.enc -out $(pwd)/.deploy/travis_id_rsa -d

chmod 0400 $(pwd)/.deploy/travis_id_rsa

<<<<<<< HEAD
ssh -t -i $(pwd)/.deploy/travis_id_rsa travis@jersey1.rkeplin.com 'sed -i "s/image: rkeplin\/bible-php-api:[a-zA-Z0-9]*/image: rkeplin\/bible-php-api:'"$TRAVIS_BUILD_NUMBER"'/g" /opt/stacks/bible.yml'
ssh -t -i $(pwd)/.deploy/travis_id_rsa travis@jersey1.rkeplin.com 'docker stack deploy -c /opt/stacks/bible.yml bible'
=======
ssh -t -oStrictHostKeyChecking=no -i $(pwd)/.deploy/travis_id_rsa travis@jersey1.rkeplin.com 'sed -i "s/image: rkeplin\/bible-php-api:[a-zA-Z0-9]*/image: rkeplin\/bible-php-api:'"$TRAVIS_BUILD_NUMBER"'/g" /opt/stacks/bible.yml'
ssh -t -oStrictHostKeyChecking=no -i $(pwd)/.deploy/travis_id_rsa travis@jersey1.rkeplin.com 'docker stack deploy -c /opt/stacks/bible.yml bible'
>>>>>>> Setup deployment script

rm -rf $(pwd)/.deploy/travis_id_rsa
