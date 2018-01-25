<?php

declare(strict_types=1);

/*
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IOTA\Tests;

use IOTA\Cryptography\Hashing\CurlFactory;
use IOTA\Cryptography\Hashing\KerlFactory;
use IOTA\Node;
use IOTA\Type\Address;
use IOTA\Type\Approvee;
use IOTA\Type\Bundle;
use IOTA\Type\BundleHash;
use IOTA\Type\Seed;
use IOTA\Type\Tag;
use IOTA\Type\Transaction;
use IOTA\Type\TransactionHash;
use IOTA\Type\Trytes;

/**
 * Class DummyData.
 *
 * Some reliable dummy data.
 */
class DummyData
{
    const CHRS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ9';

    /**
     * A list of transaction hashes.
     *
     * @var TransactionHash[]
     */
    protected static $transactionHashes;

    /**
     * A list of addresses.
     *
     * @var Address[]
     */
    protected static $addresses;

    /**
     * A list of Approvees.
     *
     * @var Approvee[]
     */
    protected static $approvees;

    /**
     * A list of nodes.
     *
     * @var Node[]
     */
    protected static $nodes;

    /**
     * A list of trytes.
     *
     * @var Trytes[]
     */
    protected static $trytes;

    /**
     * A list of bundle hashes.
     *
     * @var BundleHash[]
     */
    protected static $bundleHashes;

    /**
     * A list of bundles.
     *
     * @var Bundle[]
     */
    protected static $bundles;

    /**
     * A list of tags.
     *
     * @var Tag[]
     */
    protected static $tags;

    /**
     * A list of transactions.
     *
     * @var Transaction[]
     */
    protected static $transactions;

    /**
     * A list of seeds.
     *
     * @var Seed[]
     */
    protected static $seeds;

    /**
     * Initializes the dummy data.
     */
    public static function init()
    {
        $container = new Container();
        self::$transactionHashes = [];
        self::$bundleHashes = [];
        self::$addresses = [];
        self::$approvees = [];
        self::$seeds = [];
        self::$tags = [];
        self::$nodes = [];
        self::$trytes = [];
        self::$transactions = [];
        self::$bundles = [];
        for ($i = 0; $i < 5; ++$i) {
            self::$transactionHashes[] = new TransactionHash(
                self::generateTrytes('TRANSACTIONHASH'.self::CHRS[$i], 81)
            );
            self::$bundleHashes[] = new BundleHash(
                self::generateTrytes('BUNDLEHASH'.self::CHRS[$i], 81)
            );
            self::$addresses[] = new Address(
                self::generateTrytes('ADDRESS'.self::CHRS[$i], 81)
            );
            self::$approvees[] = new Approvee(
                self::generateTrytes('APPROVEE'.self::CHRS[$i], 81)
            );
            self::$seeds[] = new Seed(
                self::generateTrytes('SEED'.self::CHRS[$i], 81)
            );
            self::$tags[] = new Tag(
                self::generateTrytes('TAG'.self::CHRS[$i], 27)
            );
            self::$nodes[] = new Node('https://phpunitnode:1234'.$i);
            self::$trytes[] = new Trytes(self::generateTrytes('', 81));

            self::$transactions[] = new Transaction($container->get(CurlFactory::class), 'E9KERWN9XNHRPXLIVLXOGMTQLQCTARVHXNIKGRHMCPKHFWZFBODHDLJLCYKSWEETPKYZCMSOJVNBRBRFSKBBJPWLYUWVZWUFRCVYICAGWEALBTOLZILVZIFWSHOAVDHPDCQJQJHZPPKXITULYURGQDQJPMKEZY9XLXMQPJFISJRTUEKRLJVFQXIXJDRGQFGGSFHAUOBCAR9IWDKHLNROZQDNZMXJJMILVGXFMZJZBRRZLGHQFXJKHKMGKNLKTUINXZASURRKGFCMCXRBOOWUFYJTVDKCJHU99CVTCZKXQEB9GOEHZFMPICDCQORRKVUUHVJGCAJS9FXMONXOKOCSNADFTY9FETUADBOHNHFUAHRTBXXMOTKKOFDTZDGEDGXSDOGBRYEYGNXAOVLDWKY9QRATIMYAGTDXUVXEANZRLCHNQZNFUWQ9ORZNYXHGOOMEIHUKPQUSEIOYKLZPGNVOMXJVCKBNOJPUXNSISPNINNUSKRVMEVMUHLPUIBPBVRZHFYARQSFA9OKNEFTLDTSOHCR9YYXIXZ9HLMT9UMSHY9IJRZDZNCNQD9TXMSNRDSFMJUVPNGCWQTZARNWQVDOPUJNVHHBCUELQDDFTDKZ9SNJKFNAA9HNFYY9NOWNFZUEOG9LVL9FOLJJWEZEUXQSRFDSXIYIJITEYEDHFLKYDDXOMBPWIXPVIFYWNYVFSEHSXQMNCWICYNITFVPVOWFFA9LJ9LFWDKMPAX9OMAWTIBRSENFWBXNELQWHXAYNUWHGOHHMCXGESH9YNFZZYHKRRHIINXGLIOMDAJUHBFYWVAXK9DIZPQK9OQURLSBFTXEPRPIWOTHFO9XFPYAXJVSWKDLMJXZMHKPPZYQDIIBWD9EFYKOPUGSANCREYMOJQLIQRHWBRW9LCQSPUJAFZEQABCUCOCKZYJIPXCWZTPYHXZ9YQ9RHNAZMLLSFLREKPBASXHHSRNIXMAWDHOCLUKJOWFRMQFITMQPQSUAQY9KIAZNKARQLCBGIBFWAZDAQTHUQKXUNJOXGLFKGQUMGJVJDIFF9OX9GQSWQSCLGPYYYR9PNNTEITLAEXZDHXSKEQNJTEPSSDRTHALJDDFSGUETZQHEYUKY9HPZWIWSQIQNCBXLYIYYZOSHTPMACUTPFQINPCSKLUJCPNNQBTYFJJVMDUATBARJKRGNKJLQIZCVDCXXDHKXMAXGLLXGTPDKIZGLCXDXNNTGZPLHBZAJTL9KIJTERFVWSMWFJXSNKFWNPSXXLHQN99LHKUVFJWMHBMFQVGMMGVPSYZHTTTIEVVFFHSW9WQSDSHJVMJUXVXJPYRBOIQZJYYVKKQDLPGBUZDBOETYQDGTBNTPPMQYRLTDXUYRMHUOVWPZSUOVAIUNGPZSDXORHHCIGENZ9PBHRYZFBVGCTSITIZKFIQAGCAZJ9GXNDABGCCXILGAF9KKHWBVHM9VLHITEH9XELJM9VYPOSFNVMGNCBBNIPXZZUUCKIE9CBBUYLHSDXSFGJWLPVDDHBEITEAUBDUMZGFGOIYHGLHAO9LWVLEV9AGAVHMJMMQKKDNOC9ALWSHHSOWQK9EVIELCJFWYSNLAIKVXXSFSB9IGRCUENAWQWFLHIXKFXBVSEKTUHVATPOINP9AKLVKPYVPNPTLXSEMLDNGPYL9EBGISLCNQUQOJUMBYNDJRLJDHBFFRHDROZDRIYX9ECWCC9ZKOCYVHHQRSPQWWASHECEPDFTGMNHQYGTY9UXU9AASPZXUYZNYNGHV9UXAHMLEJHDPXOHDL9UYZWBF9DJGDYKS9YCSHLSYYNGPBNIR9ECZZGUOTFZZVQEJLLMPM9TVKOSVRFXFNQDSEBRTETBARLHKFVWGOMURYVVMIVYIFFWP9OIVJWXJDZGBGN9AMFUKHXJXUJPIIAEZCGVHXBJDUCHPMGFVHHTFZOT9JQ9EEWKQJAGBNXFSUSQGW9XKGHLGTLQDZMZZKVSXEPI9EYKYNLNEMMLVCPHFKHRSMHHIKVPRGCGIBGQMUEXCPHHZTNPICSNU9GEDCJNHHIJPNBCIFO9CEOKLVKWONJRRBALRVBQHDG9VGDJRZJWTZHXOZQXLCUXNCPFHMC9RNKXWMMT9AJDDNTDID9ZJWDYXGU9QUQRIXUKGZXDLEIEIHAYROJNADHNDAAUBLOFZOSQWFOAUTZOXDKNNBMHVN9MOKPWCHICGJZXKE9GSUDXZYUAPLHAKAHYHDXNPHENTERYMMBQOPSQIDENXKLKCEYCPVTZQLEEJVYJZV9BWU999999999999999999999999999NYJJ99999999999999999999999NZYOYXD99999999999A99999999NCAVHDAXXGRKJGNQSDKWGMSXPNWYDEQUGS9PNCN9WGIERXCPDFAENLL9JCPDXAKYWUGL9DNIHEKOLOUVZXNNKVSEGANEJXHEGYVPLBMKYMVFBDYPQFSNUPFNQAUKDMPCMOWMAFAOSOCNPWVYLBBLRBXPRJGMO99999PYWODXPIBRUMXTSPQBZCFZHCRDMLS9NDJGKPJNFLOWXTDHOGOHALWTQJZPGOXRYIHQLGYKDINRNT99999VLCXAQHGNNLFXEDLJKOGUJ9YZMPLJAVIZ9OFUGCQNKLKSBRSXHAPX9TBCVVRCQSANFHQXPBNEPZLHR9UC');
            self::$bundles[] = (new Bundle(
                $container->get(KerlFactory::class),
                $container->get(CurlFactory::class)
            ))
                ->setBundleHash(self::$bundleHashes[$i])
                ->addTransaction(self::$transactions[$i]);
        }
    }

    /**
     * Gets a transaction hash at the given position.
     *
     * @param int $index
     *
     * @return TransactionHash
     */
    public static function getTransactionHash(int $index = 0): TransactionHash
    {
        return self::$transactionHashes[$index];
    }

    /**
     * @param int $index
     *
     * @return Address
     */
    public static function getAddress(int $index = 0): Address
    {
        return self::$addresses[$index];
    }

    /**
     * @param int $index
     *
     * @return Node
     */
    public static function getNode(int $index = 0): Node
    {
        return self::$nodes[$index];
    }

    /**
     * @param int $index
     *
     * @return Trytes
     */
    public static function getTrytes(int $index = 0): Trytes
    {
        return self::$trytes[$index];
    }

    /**
     * @param int $index
     *
     * @return BundleHash
     */
    public static function getBundleHash(int $index = 0): BundleHash
    {
        return self::$bundleHashes[$index];
    }

    /**
     * @param int $index
     *
     * @return Tag
     */
    public static function getTag(int $index = 0): Tag
    {
        return self::$tags[$index];
    }

    /**
     * @param int $index
     *
     * @return Approvee
     */
    public static function getApprovee(int $index = 0): Approvee
    {
        return self::$approvees[$index];
    }

    /**
     * @param int $index
     *
     * @return Bundle
     */
    public static function getBundle(int $index = 0): Bundle
    {
        return self::$bundles[$index];
    }

    /**
     * @param int $index
     *
     * @return Transaction
     */
    public static function getTransaction(int $index = 0): Transaction
    {
        return self::$transactions[$index];
    }

    /**
     * @param int $index
     *
     * @return Seed
     */
    public static function getSeed(int $index = 0): Seed
    {
        return self::$seeds[$index];
    }

    /**
     * @param string $prefix
     * @param int    $length
     *
     * @return string
     */
    private static function generateTrytes(string $prefix, int $length)
    {
        $trytes = $prefix;
        for ($i = 0; $i < $length - strlen($prefix); ++$i) {
            $trytes .= self::CHRS[random_int(0, 26)];
        }

        return $trytes;
    }
}
