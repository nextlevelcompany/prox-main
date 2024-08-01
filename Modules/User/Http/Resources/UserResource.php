<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @package App\Http\Resources\Tenant
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        $modules = $this->getCurrentModuleByTenant()
            ->pluck('module_id')
            ->toArray();
        $levels = $this->getCurrentModuleLevelByTenant()
            ->pluck('module_level_id')
            ->toArray();

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'address' => $this->address,
            'annular_purchase' => $this->annular_purchase,
            'api_token' => $this->api_token,
            'change_seller' => (bool)$this->change_seller,
            'contract_date' => $this->contract_date,
            'corporate_cell_phone' => $this->corporate_cell_phone,
            'corporate_email' => $this->corporate_email,
            'create_payment' => $this->create_payment,
            'date_of_birth' => $this->date_of_birth,
            'default_document_types' => $this->default_document_types->transform(function ($row) {
                return $row->getDataMultipleDocumentType();
            }),
            'delete_payment' => $this->delete_payment,
            'delete_purchase' => $this->delete_purchase,
            'document_id' => $this->document_id,
            'edit_purchase' => $this->edit_purchase,
            'establishment_id' => $this->establishment_id,
            'identity_document_type_id' => $this->identity_document_type_id,
            'last_names' => $this->last_names,
            'levels' => $levels,
            'locked' => (bool)$this->locked,
            'modules' => $modules,
            'multiple_default_document_types' => $this->multiple_default_document_types,
            'names' => $this->names,
            'number' => $this->number,
            'permission_edit_cpe' => $this->permission_edit_cpe,
            'permission_edit_sale_note' => (bool)$this->permission_edit_sale_note,
            'permission_force_send_by_summary' => $this->permission_force_send_by_summary,
            'personal_cell_phone' => $this->personal_cell_phone,
            'personal_email' => $this->personal_email,
            'photo_filename' => $this->photo_filename,
            'photo_temp_image' => $this->getPhotoForView(),
            'photo_temp_path' => null,
            'position' => $this->position,
            'recreate_documents' => $this->recreate_documents,
            'series_id' => ($this->series_id == 0) ? null : $this->series_id,
            'type' => $this->type,
            'zone_id' => $this->zone_id,
        ];
    }
}
