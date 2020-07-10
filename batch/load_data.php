<?php
use Fidry\AliceDataFixtures\Bridge\Propel2\Persister\ModelPersister;
use Fidry\AliceDataFixtures\Loader\PersisterLoader;
use Fidry\AliceDataFixtures\Loader\SimpleLoader;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\Loader\SimpleFilesLoader;
use Nelmio\Alice\Parser\Chainable\YamlParser;
use Propel\Runtime\Propel;
use Symfony\Component\Yaml\Parser;

use App\Entity\Question;

require dirname(__DIR__).'/vendor/autoload.php';

// setup Propel
require dirname(__DIR__).'/config/config.php';

$connection = Propel::getConnection('askeet');

$modelPersister = new ModelPersister($connection);

$loader = new PersisterLoader(
    new SimpleLoader(
        new SimpleFilesLoader(
            new YamlParser(new Parser()),
            new NativeLoader()
            )
        ),
            $modelPersister,
            null,
            []
        );

$loader->load([
             dirname(__DIR__).'/data/fixtures/test_data.yml'
        ]);


