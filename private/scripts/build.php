<?php
include_once($_SERVER['HOME'] .'/code/private/scripts/util.php');
  
// TODO: fail gracefully if we are not in SFTP mode.
echo "\n=======================\n";
echo "  Now Build, my pretty!  ";
echo "\n=======================\n";

echo `cd $workspace && wp core download`;
// TODO: this should force add anything.
// TODO: this should have a pretty author.
echo `cd $workspace && git add *`;
echo `cd $workspace && git commit -am "Build artifacts"`;
// Push build artifacts to Pantheon's internal repo.
// TODO: This could trigger circular pushes. Fix that.
echo `cd $workspace && git push origin master`;