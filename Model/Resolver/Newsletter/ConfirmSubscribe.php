<?php

declare(strict_types=1);

namespace StorefrontX\CustomerGraphQlExtended\Model\Resolver\Newsletter;

use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\EnumLookup;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Newsletter\Model\Subscriber;

/**
 * Resolver class for the `confirmSubscribeEmailToNewsletter` mutation.
 */
class ConfirmSubscribe implements ResolverInterface
{
    /**
     * Subscriber factory
     *
     * @var SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @var EnumLookup
     */
    private $enumLookup;

    /**
     * ConfirmSubscribe constructor.
     *
     * @param SubscriberFactory $subscriberFactory
     * @param EnumLookup $enumLookup
     */
    public function __construct(
        SubscriberFactory $subscriberFactory,
        EnumLookup $enumLookup
    ) {
        $this->subscriberFactory = $subscriberFactory;
        $this->enumLookup = $enumLookup;
    }

    /**
     * @inheritDoc
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $id = $args['subscriber_id'];

        if (empty($id)) {
            throw new GraphQlInputException(
                __('You must specify an subscriber id to confirm subscribe for newsletter.')
            );
        }

        $code = $args['subscriber_confirm_code'];

        if (empty($code)) {
            throw new GraphQlInputException(
                __('You must specify an subscriber confirm code to confirm subscribe for newsletter.')
            );
        }

        if ($id && $code) {
            $subscriber = $this->subscriberFactory->create()->load($id);

            if ($subscriber->getId() && $subscriber->getCode()) {
                if ($subscriber->confirm($code)) {
                    $status = $this->enumLookup->getEnumValueFromField(
                        'SubscriptionStatusesEnum',
                        (string)$subscriber->getSubscriberStatus()
                    );
                } else {
                    throw new GraphQlInputException(
                        __('This is an invalid subscription confirmation code.')
                    );
                }
            } else {
                throw new GraphQlInputException(
                    __('This is an invalid subscription ID')
                );
            }
        }

        return [
            'status' => $status
        ];
    }

}
