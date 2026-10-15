<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Unit tests for web service contract comparisons.
 *
 * @package   local_backupftp
 * @copyright 2026 Eduardo Kraus {@link https://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_backupftp;

/**
 * Contract snapshot validation and comparison.
 *
 * @covers \local_backupftp\\service_contract
 */
final class service_contract_test extends \advanced_testcase {
    /**
     * Build a minimal snapshot.
     *
     * @param array $functions Functions.
     * @return array
     */
    private function fixture(array $functions): array {
        return [
            'format' => service_contract::FORMAT,
            'moodle' => ['release' => '4.5', 'version' => '2024100700'],
            'functions' => $functions,
        ];
    }

    /**
     * Compare signatures and detect additions and removals.
     *
     * @return void
     */
    public function test_added_removed_and_changed_functions(): void {
        $signature = ['kind' => 'value', 'type' => 'int'];
        $old = $this->fixture([
            'local_test_removed' => ['status' => 'ok', 'parameters' => $signature, 'returns' => $signature],
            'local_test_modified' => ['status' => 'ok', 'parameters' => $signature, 'returns' => $signature],
        ]);
        $new = $this->fixture([
            'local_test_added' => ['status' => 'ok', 'parameters' => $signature, 'returns' => $signature],
            'local_test_modified' => ['status' => 'ok',
                'parameters' => ['kind' => 'value', 'type' => 'text'], 'returns' => $signature],
        ]);
        $result = service_contract::compare($old, $new);
        $this->assertSame(1, $result['counts']['added']);
        $this->assertSame(1, $result['counts']['removed']);
        $this->assertSame(1, $result['counts']['changed']);
        $this->assertSame(0, $result['counts']['unchanged']);
    }

    /**
     * The comparison must not depend on JSON associative key ordering.
     *
     * @return void
     */
    public function test_reordered_structure_is_unchanged(): void {
        $old = $this->fixture(['local_test_function' => [
            'status' => 'ok', 'parameters' => ['kind' => 'object',
                'fields' => ['b' => ['type' => 'text'], 'a' => ['type' => 'int']]],
            'returns' => ['kind' => 'void'],
        ]]);
        $new = $this->fixture(['local_test_function' => [
            'returns' => ['kind' => 'void'], 'status' => 'ok',
            'parameters' => ['fields' => ['a' => ['type' => 'int'], 'b' => ['type' => 'text']], 'kind' => 'object'],
        ]]);
        $this->assertSame(1, service_contract::compare($old, $new)['counts']['unchanged']);
    }

    /**
     * Reject documents that are not generated with the supported schema.
     *
     * @return void
     */
    public function test_invalid_snapshot_rejected(): void {
        $this->expectException(\invalid_parameter_exception::class);
        service_contract::decode('{"format":"wrong","functions":{}}');
    }

    /**
     * Valid exported files round-trip without credentials or server access.
     *
     * @return void
     */
    public function test_valid_snapshot_roundtrip(): void {
        $fixture = $this->fixture(['core_course_get_courses' => [
            'status' => 'ok', 'parameters' => ['kind' => 'object', 'fields' => []],
            'returns' => ['kind' => 'list', 'items' => ['kind' => 'value', 'type' => 'int']],
        ]]);
        $this->assertSame($fixture, service_contract::decode(json_encode($fixture)));
    }

    /**
     * An unresolved description is not treated as compatible.
     *
     * @return void
     */
    public function test_unresolved_contract(): void {
        $old = $this->fixture(['local_test_fn' => ['status' => 'unresolved']]);
        $new = $this->fixture(['local_test_fn' => [
            'status' => 'ok', 'parameters' => ['kind' => 'void'], 'returns' => ['kind' => 'void'],
        ]]);
        $this->assertSame(1, service_contract::compare($old, $new)['counts']['unresolved']);
    }
}
