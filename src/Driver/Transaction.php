<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace KryuuCommon\BigChainDb\Driver;


use KryuuCommon\Base58\Base58;
use KryuuCommon\BigChainDB\Entity\Transaction;
use KryuuCommon\BigChainDB\Exception\TypeException;
use KryuuCommon\JsonStableStringify\Json;

/**
 * Description of Transaction
 *
 * @author spawn
 */
class Transaction {
    /**
     * Canonically serializes a transaction into a string by sorting the keys
     * @param {Object} (transaction)
     * @return {string} a canonically serialized Transaction
     */
    public static function serializeTransactionIntoCanonicalString($transaction) {
        // BigchainDB signs fulfillments by serializing transactions into a
        // "canonical" format where
        $tx = clone($transaction);
        // TODO: set fulfillments to null
        // Sort the keys
        $stableJson = new Json();
        return $stableJson->stringify($tx, function($a, $b) {
            return $a->key > $b->key ? 1 : -1;
        });
    }

    public static function makeInputTemplate($publicKeys = [], $fulfills = null, $fulfillment = null) {
        return [
            $fulfillment,
            $fulfills,
            'owners_before' => $publicKeys,
        ];
    }

    /**
     * @return KryuuCommon\BigChainDB\Entity\Transaction
     */
    public static function makeTransactionTemplate() {
        return new Transaction();
    }

    public static function makeTransaction($operation, $asset, $metadata = null, $outputs = [], $inputs = []) {
        return Transaction::makeTransactionTemplate()
            ->setOperation($operation)
            ->setAsset($asset)
            ->setMetadata($metadata)
            ->setInputs($inputs)
            ->setOutputs($outputs);
    }

    /**
     * Generate a `CREATE` transaction holding the `asset`, `metadata`, and `outputs`, to be signed by
     * the `issuers`.
     * @param {Object} asset Created asset's data
     * @param {Object} metadata Metadata for the Transaction
     * @param {Object[]} outputs Array of Output objects to add to the Transaction.
     *                           Think of these as the recipients of the asset after the transaction.
     *                           For `CREATE` Transactions, this should usually just be a list of
     *                           Outputs wrapping Ed25519 Conditions generated from the issuers' public
     *                           keys (so that the issuers are the recipients of the created asset).
     * @param {...string[]} issuers Public key of one or more issuers to the asset being created by this
     *                              Transaction.
     *                              Note: Each of the private keys corresponding to the given public
     *                              keys MUST be used later (and in the same order) when signing the
     *                              Transaction (`signTransaction()`).
     * @returns {Object} Unsigned transaction -- make sure to call signTransaction() on it before
     *                   sending it off!
     */
    public static function makeCreateTransaction($asset, $metadata, $outputs, ...$issuers) {
        $assetDefinition = [
            'data' => $asset || null,
        ];
        
        $inputs = array_map(function($issuer){
            return Transaction::makeInputTemplate([$issuer]);
        }, $issuers);

        return Transaction::makeTransaction('CREATE', $assetDefinition, $metadata, $outputs, $inputs);
    }

    /**
     * Create an Ed25519 Cryptocondition from an Ed25519 public key
     * to put into an Output of a Transaction
     * @param {string} publicKey base58 encoded Ed25519 public key for the recipient of the Transaction
     * @param {boolean} [json=true] If true returns a json object otherwise a crypto-condition type
     * @returns {Object} Ed25519 Condition (that will need to wrapped in an Output)
     */

    /**
     * Create an Ed25519 Cryptocondition from an Ed25519 public key
     * to put into an Output of a Transaction
     * @param {string} publicKey base58 encoded Ed25519 public key for the recipient of the Transaction
     * @param {boolean} [json=true] If true returns a json object otherwise a crypto-condition type
     * @returns {Object} Ed25519 Condition (that will need to wrapped in an Output)
     */
    public static function makeEd25519Condition($publicKey, $json = true) {
        $buffer = new Buffer();
        $publicKeyBuffer = $buffer->from($publicKey, 'base58')->toString();

        /**
         * @todo Need to rebuild CryptoConditions for this;
         */
        //const ed25519Fulfillment = new cc.Ed25519Sha256()
        //ed25519Fulfillment.setPublicKey(publicKeyBuffer)

        if ($json) {
            return ccJsonify($ed25519Fulfillment);
        }

        return $ed25519Fulfillment;
    }

    /**
     * Create an Output from a Condition.
     * Note: Assumes the given Condition was generated from a
     * single public key (e.g. a Ed25519 Condition)
     * @param {Object} condition Condition (e.g. a Ed25519 Condition from `makeEd25519Condition()`)
     * @param {string} amount Amount of the output
     * @returns {Object} An Output usable in a Transaction
     */
    public static function makeOutput($condition, $amount = '1') {
        if ($amount !== 'string') {
            throw new Exception('`amount` must be of type string');
        }
        $publicKeys = [];
        $getPublicKeys = function($details) use ($publicKeys, $getPublicKeys) {
            if ($details['type'] === 'ed25519-sha-256') {
                if (!in_array($details['public_key'], $publicKeys)) {
                    array_push($details['public_key'], $publicKeys);
                }
            } else if ($details['type'] === 'threshold-sha-256') {
                // details.subconditions.map(getPublicKeys);
                foreach ($details['subconditions'] as $index => $var) {
                    $details['subconditions'][$index] = $getPublicKeys($var);
                }
            }
        };
        $getPublicKeys($condition['details']);
        return [
            $condition,
            'amount' => $amount,
            'public_keys' => $publicKeys,
        ];
    }

    /**
     * Create a Preimage-Sha256 Cryptocondition from a secret to put into an Output of a Transaction
     * @param {string} preimage Preimage to be hashed and wrapped in a crypto-condition
     * @param {boolean} [json=true] If true returns a json object otherwise a crypto-condition type
     * @returns {Object} Preimage-Sha256 Condition (that will need to wrapped in an Output)
     */
    public static function makeSha256Condition($preimage, $json = true) {
        /**
         * @todo Need to rebuild CryptoConditions for this;
         */
        $sha256Fulfillment = new cc.PreimageSha256();
        $buffer = new Buffer();
        $sha256Fulfillment['preimage'] = $buffer->from($preimage);

        if ($json) {
            return ccJsonify($sha256Fulfillment);
        }
        return $sha256Fulfillment;
    }

    /**
     * Create an Sha256 Threshold Cryptocondition from threshold to put into an Output of a Transaction
     * @param {number} threshold
     * @param {Array} [subconditions=[]]
     * @param {boolean} [json=true] If true returns a json object otherwise a crypto-condition type
     * @returns {Object} Sha256 Threshold Condition (that will need to wrapped in an Output)
     */
    public static function makeThresholdCondition($threshold, $subconditions = [], $json = true) {
        $thresholdCondition = new cc.ThresholdSha256();
        $thresholdCondition['threshold'] = $threshold;

        foreach ($subconditions as $subcondition) {
            $thresholdCondition->addSubfulfillment($subcondition);
        }

        if ($json) {
            return ccJsonify($thresholdCondition);
        }

        return $thresholdCondition;
    }

    /**
     * Generate a `TRANSFER` transaction holding the `asset`, `metadata`, and `outputs`, that fulfills
     * the `fulfilledOutputs` of `unspentTransaction`.
     * @param {Object} unspentTransaction Previous Transaction you have control over (i.e. can fulfill
     *                                    its Output Condition)
     * @param {Object} metadata Metadata for the Transaction
     * @param {Object[]} outputs Array of Output objects to add to the Transaction.
     *                           Think of these as the recipients of the asset after the transaction.
     *                           For `TRANSFER` Transactions, this should usually just be a list of
     *                           Outputs wrapping Ed25519 Conditions generated from the public keys of
     *                           the recipients.
     * @param {...number} OutputIndices Indices of the Outputs in `unspentTransaction` that this
     *                                     Transaction fulfills.
     *                                     Note that listed public keys listed must be used (and in
     *                                     the same order) to sign the Transaction
     *                                     (`signTransaction()`).
     * @returns {Object} Unsigned transaction -- make sure to call signTransaction() on it before
     *                   sending it off!
     */
    // TODO:
    // - Make `metadata` optional argument
    public static function makeTransferTransaction(
        $unspentOutputs,
        $outputs,
        $metadata
    ) {
        $inputs = array_map( function($unspentOutput) use ($outputs) {
            list($tx, $outputIndex) = [ $unspentOutput['tx'], $unspentOutput['output_index'] ];
            $fulfilledOutput = $tx[$outputs['outputIndex']];
            $transactionLink = [
                'output_index' => $outputIndex,
                'transaction_id' => $tx['id'],
            ];

            return Transaction::makeInputTemplate($fulfilledOutput['public_keys'], $transactionLink);
        });

        $assetLink = [
            'id' => ($unspentOutputs[0]['tx']['operation'] === 'CREATE' ? $unspentOutputs[0]['tx']['id']
                : $unspentOutputs[0]['tx']['asset']['id'])
        ];
        return Transaction::makeTransaction('TRANSFER', $assetLink, $metadata, $outputs, $inputs);
    }

    /**
     * Sign the given `transaction` with the given `privateKey`s, returning a new copy of `transaction`
     * that's been signed.
     * Note: Only generates Ed25519 Fulfillments. Thresholds and other types of Fulfillments are left as
     * an exercise for the user.
     * @param {Object} transaction Transaction to sign. `transaction` is not modified.
     * @param {...string} privateKeys Private keys associated with the issuers of the `transaction`.
     *                                Looped through to iteratively sign any Input Fulfillments found in
     *                                the `transaction`.
     * @returns {Object} The signed version of `transaction`.
     */
    public static function signTransaction($transaction, $privateKeysArg) {
        $signedTx = clone($transaction);
        $privateKeys = func_get_args();
        array_shift($privateKeys);
        $serializedTransaction =
            Transaction::serializeTransactionIntoCanonicalString($transaction);
        
        $buffer = new Buffer();

        foreach ($signedTx->getInputs() as $index => $input) {
            $privateKey = $privateKeys[$index];
            $privateKeyBuffer = $buffer->from($privateKey, 'base58')->get();

            $transactionUniqueFulfillment = $input['fulfills'] 
                ? array_merge($serializedTransaction, 
                    $input['fulfills']['transaction_id'],
                    input['fulfills']['output_index'])
                : $serializedTransaction;
            $transactionHash = hash('sha256', $transactionUniqueFulfillment);        
            
            /**
             * @todo Need to rebuild CryptoConditions for this;
             */
            $ed25519Fulfillment = new cc.Ed25519Sha256();
            
            $ed25519Fulfillment->sign($buffer->from($transactionHash, 'hex'), $privateKeyBuffer);
            $fulfillmentUri = $ed25519Fulfillment->serializeUri();

            $input['fulfillment'] = $fulfillmentUri;
        }

        $serializedSignedTransaction =
            Transaction::serializeTransactionIntoCanonicalString($signedTx);
        $signedTx['id'] = hash('sha256', $serializedSignedTransaction);
        return $signedTx;
    }
}
