<?php

namespace SessionManager\TableModels;

use RuntimeException;

use GroupType\Model\GroupType;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature;
use Zend\Db\Sql\Select;


class GroupTypeTableGateway extends AbstractTableGateway
{
    public function __construct()
    {
        $this->table      = 'groupTypes';
        $this->featureSet = new Feature\FeatureSet();
        $this->featureSet->addFeature(new Feature\GlobalAdapterFeature());
        $this->initialize();
    }


    /**
     * Selects all GroupTypes from the database.
     *
     * @return GroupType[]
     */
    public function fetchAll()
    {
        return $this->select();
    }

    /**
     * Selects an GroupType from the database
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return GroupType
     */
    public function getGroupType($id, $options = ['type' => 'slug'])
    {
        if ($options['type'] == 'slug')
        {
            $rowset = $this->select(['slug' => $id]);
        }
        else if ($options['type'] == 'id')
        {
            $rowset = $this->select(['id' => $id]);
        }
        $row = $rowset->current();
        if (! $row)
        {
            throw new RuntimeException(sprintf(
                'Could not Find Row with identifier %s of type %s',
                $id, $options['type']
            ));
        }

        return $row;
    }

    /**
     * Checks if an groupType exists in the database.
     *
     * @param mixed $id The identifier.
     * @param dictionary $options Contains 'type' which defines what type of
     * identifier $id is. Default value is 'type' => 'id'.
     * @return boolean If value exists
     */
    public function groupTypeExists($id, $options = ['type' => 'id'])
    {
        return (new RecordExists([
            'table' => $this->getTable(),
            'field' => $options['type'],
            'adapter' => $this->getAdapter(),
        ]))->isValid($id);
    }

    /**
     * Saves an GroupType to the database.
     *
     * If $groupType->slug is not null then attempts to update an groupType with that slug
     *
     * @param GroupType $groupType
     * @return void
     * @throws RuntimeException GroupType does not exist
     */
    public function saveGroupType(GroupType $groupType)
    {
        $data = [
            'name' => $groupType->name,
            'description' => $groupType->description
        ];

        $slug = $groupType->slug;

        if ($slug == NULL)
        {
            do
            {
                $data['slug'] = GroupType::generateSlug();
            }
            while ($this->groupTypeExists($data['slug'], ['type' => 'slug']));
            $this->insert($data);
            return;
        }

        if ($dbGroupType = $this->getGroupType($slug))
        {
            $this->update($data, ['slug' => $slug]);
        }
        else
        {
            throw new RuntimeException(springf(
                'Cannot update groupType with identifier %d; does not exist',
                $id
            ));
        }
    }

    /**
     * Deletes GroupType and deletes the GroupType's icon.
     *
     * @param String $slug GroupType's slug.
     * @return void
     */
    public function deleteGroupType($slug)
    {
        $this->delete(['slug' => $slug]);
    }
}
