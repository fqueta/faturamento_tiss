<?
namespace App\Qlib;
use Illuminate\Support\Facades;

/**
 *
 */
class QlibFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'qlib';
    }

}
